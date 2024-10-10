<?php

namespace app\config;

use PDO;
use PDOException;
use Exception;
use Dotenv\Dotenv;

class conexion
{
    private $conexion;
    private $data;

    public function __construct()
    {      
        try {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $requiredEnvVars = ['HOST', 'DB', 'DB_USER', 'CHARSET'];
        foreach ($requiredEnvVars as $envVar) {
            if (!isset($_ENV[$envVar])) {
                die("La variable de entorno $envVar no está definida.");
            }
        }

        $this->data = [
            'HOST' => $_ENV['HOST'] ?? '',
            'DB' => $_ENV['DB'] ?? '',
            'USER' => $_ENV['DB_USER'] ?? '',
            'PASS' => $_ENV['PASS'] ?? '',
            'CHARSET' => $_ENV['CHARSET'] ?? '',
        ];

        /*   foreach ($this->data as $key => $value) {
            if (empty($value)) {
                die("La variable de entorno $key no puede estar vacía o nula.");
            }
        } */
        foreach ($this->data as $key => $value) {
            if (empty($value) && $key !== 'PASS') {
                die("La variable de entorno $key no puede estar vacía o nula.");
            }
        }
        try {
            $opt = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
            $dsn = "mysql:host=" . $this->data['HOST'] . ";dbname=" . $this->data['DB'] . ";" . $this->data['CHARSET'];
            if (!$this->conexion instanceof PDO) {
                $this->conexion = new PDO($dsn, $this->data['USER'], $this->data['PASS'], $opt);
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {
            error_log('Error en la conexión: ' . $e->getMessage());
            die(json_encode(Response::estado500()));
        }
    }

    public function conectar()
    {
        if ($this->conexion instanceof PDO) {
            error_log('Conexión exitosa');
            return $this->conexion;
        } else {
            die("La conexión no está configurada correctamente.");
        }
    }
}
