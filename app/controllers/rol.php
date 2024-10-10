<?php

namespace app\controllers;

use Exception;
use app\config\view;
use app\config\seguridad;
use app\config\response;
use app\models\rolModel;
use app\config\controller;

class rol extends controller
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new rolModel();
    }
    public function index()
    {
        if ($this->method !== 'GET') {
            $this->response(Response::estado405());
        }
        $view = new view();

       try {
           
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('rol', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(Response::estado404($e));
        } 
    }
    public function getRoles()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        seguridad::validateToken($this->header, seguridad::secretKey()); 
        try {
            $roles = $this->model->getRoles();
            if (empty($roles)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($roles));
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }
    public function getRol($id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
       seguridad::validateToken($this->header, seguridad::secretKey()); 
        try {
            $rol = $this->model->getRol($id);
            if (empty($rol)) {
                http_response_code(204);
                return $this->response(response::estado204());
            }
            http_response_code(200);
            return $this->response(response::estado200($rol));
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }

    public function createRol()
    {
        if ($this->method !== 'POST') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        seguridad::validateToken($this->header, seguridad::secretKey()); 
        if ($this->data === null) {
            http_response_code(400);
            return $this->response(Response::estado400('Datos JSON no válidos.'));
        }

        if (empty($this->data['nombre'])) {
            http_response_code(400);
            return $this->response(Response::estado400('El nombre es requerido.'));
        }
        $this->data['nombre'] = ucwords($this->data['nombre']);
        try {

            if (empty($this->data['id_rol'])) {
                $rol = $this->model->createRol($this->data['nombre']);
            } else {
                $rol = $this->model->updateRol($this->data);
            }
            switch ($rol) {
                case "ok":
                    http_response_code(201);
                    return $this->response(response::estado201());
                case "existe":
                    http_response_code(409);
                    return $this->response(response::estado409());
                case "error":
                    http_response_code(500);
                    return $this->response(response::estado500());
            }
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }
    public function deleteRol($id)
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
         seguridad::validateToken($this->header, seguridad::secretKey()); 
        try {
            $rol = $this->model->deleteRol($id);
            if ($rol === "ok") {
                http_response_code(200);
                return $this->response(response::estado200('ok'));
            }
            http_response_code(500);
            return $this->response(response::estado500());
        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }
}
