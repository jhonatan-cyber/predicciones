<?php

namespace app\controllers;

use app\config\controller;
use app\models\loginModel;
use app\config\response;
use app\config\seguridad;
use app\Config\view;
use Exception;

class login extends controller
{
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new loginModel();
    }
    public function index()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
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
            $this->response(response::estado404($e));
        }

    }
    public function login()
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            return $this->response(response::estado400('Error al decodificar JSON: ' . json_last_error_msg()));
        }
        $datos = ['correo', 'password'];
        $data = $this->data;
        foreach ($datos as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                return $this->response(response::estado400('Falta el campo ' . $field));
            }
        }
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            return $this->response(response::estado400('El correo no es valido'));
        }
        try {
            $res = $this->model->login($data);
            if ($res['estado'] === 'ok') {
                $_SESSION['token'] = $res['data']['token'];
                $_SESSION['usuario_id'] = $res['data']['id_usuario'];
                if ($res['data']['estado'] === 0) {
                    $_SESSION['activo'] = false;
                } else {
                    $_SESSION['activo'] = true;
                }
                return $this->response($res);
            }
            return $this->response($res);


        } catch (Exception $e) {
            http_response_code(500);
            return $this->response(response::estado500($e));
        }
    }


    public function logout()
    {
        if ($this->method !== 'GET') {
            http_response_code(405);
            return $this->response(response::estado405());
        }
        seguridad::validateToken($this->header, seguridad::secretKey());

        if (session_status() === PHP_SESSION_ACTIVE) {

            session_destroy();

        }
        http_response_code(200);
        return $this->response(response::estado200('ok'));
    }
}