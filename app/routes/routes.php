<?php


use Bramus\Router\Router;

$router = new Router();
use app\controllers\home;
use app\controllers\login;
use app\controllers\usuario;
use app\controllers\rol;

$home = new home();
$login = new login();
$usuario = new usuario();
$rol = new rol();


/*-------------Login--------------*/
$router->get('/', [$login, 'index']);
$router->post('login', [$login, 'login']);
$router->get('logout', [$login, 'logout']);

/*-------------Home--------------*/
$router->get('home', [$home, 'index']);
$router->post('uploadCsv', [$home, 'uploadExcelFile']);
$router->get('selectLastCsv', [$home, 'selectLastCsv']);

/******************** Roles ********************/
$router->get('roles', [$rol, 'index']);
$router->get('getRoles', [$rol, 'getRoles']);
$router->post('createRol', [$rol, 'createRol']);
$router->get('getRol/(\d+)', [$rol, 'getRol']);
$router->get('deleteRol/(\d+)', [$rol, 'deleteRol']);

/******************** Usuarios ********************/
$router->get('usuarios', [$usuario, 'index']);
$router->get('getUsuarios', [$usuario, 'getUsuarios']);
$router->post('createUsuario', [$usuario, 'createUsuario']);
$router->get('getUsuario/(\d+)', [$usuario, 'getUsuario']);
$router->get('deleteUsuario/(\d+)', [$usuario, 'deleteUsuario']);


$router->run();
