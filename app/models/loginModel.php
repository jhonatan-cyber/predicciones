<?php

namespace app\models;

use app\config\query;
use app\config\response;
use app\config\seguridad;
use Exception;

class loginModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function login(array $usuario)
    {
        $requiredFields = ['correo', 'password'];
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $usuario)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }

        $sql = "SELECT U.id_usuario, U.nombre, U.apellido, U.correo, U.password, U.foto, U.estado, R.nombre AS rol
                FROM usuarios AS U JOIN roles AS R ON U.rol_id = R.id_rol 
                WHERE U.correo = :correo AND U.estado = 1 LIMIT 1";
        $params = [':correo' => $usuario['correo']];
        $password = $usuario['password'];

        try {
            $res = $this->select($sql, $params);
            if (empty($res)) {
                return response::estado400('Usuario no encontrado');
            }
            if (seguridad::validatePassword($password, $res['password'])) {
                $payload = [
                    'token' => [
                        'id_usuario' => $res['id_usuario'],
                        'nombre' => $res['nombre'],
                        'apellido' => $res['apellido'],
                        'rol' => $res['rol'],
                        'foto' => $res['foto'],
                        'correo' => $res['correo']
                    ]
                ];
                $token = seguridad::createToken(seguridad::secretKey(), $payload);
                $data = [
                    'id_usuario' => $res['id_usuario'],
                    'token' => $token,
                    'estado' => $res['estado']
                ];

                return response::estado200($data);
            }

            return response::estado400('Contraseña incorrecta');
        } catch (Exception $e) {
            return response::estado500('Error en el servidor: ' . $e->getMessage());
        }
    }
}