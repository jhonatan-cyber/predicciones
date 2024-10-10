<?php

namespace app\controllers;

use Exception;
use app\config\view;
use app\config\response;
use app\models\homeModel;
use app\config\controller;
use app\config\seguridad;

class home extends controller
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new homeModel();
    }
    public function index()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        if ($this->method !== 'GET') {
            $this->response(Response::estado405());
        }
        $view = new view();

        try {

            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('home', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(Response::estado404($e));
        }


    }
    public function uploadExcelFile()
    {
        if ($this->method !== 'POST') {
            http_response_code(405);
            return $this->response(response::estado405());
        }

        seguridad::validateToken($this->header, seguridad::secretKey());

        try {
            $file = $_FILES['file'];

            // Verifica si se ha subido un archivo
            if (isset($file['name']) && !empty($file['name'])) {
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

                // Verifica que el archivo sea de tipo Excel (CSV, XLSX, XLS, etc.)
                $allowedExtensions = ['csv', 'xlsx', 'xls', 'xlsm', 'xlsb', 'xltx', 'xlt'];
                if (!in_array(strtolower($extension), $allowedExtensions)) {
                    return $this->response(response::estado400("Formato soportado: solo se permiten archivos de Excel y CSV."));
                }

                // Genera un nombre único para el archivo usando el nombre original y un timestamp
                $timestamp = date('YmdHis');  // Formato: YYYYMMDDHHMMSS
                $filename = 'public/assets/csv/' . pathinfo($file['name'], PATHINFO_FILENAME) . '_' . $timestamp . '.' . $extension;

                // Mueve el archivo a la carpeta temporal
                if (!move_uploaded_file($file['tmp_name'], $filename)) {
                    throw new Exception('Error al mover el archivo.');
                }

                // Devuelve una respuesta exitosa
                return $this->response(response::estado200('Archivo subido exitosamente: ' . $filename));
            } else {
                return $this->response(response::estado400('El archivo debe ser un archivo de Excel o CSV.'));
            }
        } catch (Exception $e) {
            return $this->response(response::estado500('Error: ' . $e->getMessage()));
        }
    }


    public function selectLastCsv()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }

        $carpetaOrigen = 'public/modelo/predicciones/';


        if (!is_dir($carpetaOrigen)) {
            return $this->response(response::estado404("Directorio no encontrado: $carpetaOrigen"));
        }


        $archivos = scandir($carpetaOrigen, SCANDIR_SORT_DESCENDING);

        if ($archivos === false) {
            return $this->response(response::estado500("Error al leer archivos en el directorio."));
        }

 
        $archivosCsv = array_filter($archivos, function ($archivo) use ($carpetaOrigen) {
            return is_file($carpetaOrigen . $archivo) && pathinfo($archivo, PATHINFO_EXTENSION) === 'csv';
        });

        if (empty($archivosCsv)) {
            return $this->response(response::estado204("No se encontraron archivos CSV en el directorio."));
        }

        // Obtener el último archivo CSV (el primero en la lista ya está ordenado)
        $ultimoArchivoCsv = $archivosCsv[0]; // El archivo CSV más reciente
        $rutaCompleta = $carpetaOrigen . $ultimoArchivoCsv;

        return $this->response(response::estado200( [
            'nombre' => $ultimoArchivoCsv,
            'ruta' => $rutaCompleta
        ]));

    }
}