<?php

namespace app\models;

use app\config\query;
use app\config\response;
use app\config\seguridad;
use Exception;

class usuarioModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getUsuarios()
    {
        $sql = "SELECT U.*, R.nombre AS rol
                FROM usuarios AS U JOIN roles AS R ON U.rol_id = R.id_rol WHERE U.estado = 1";
        try {
            return $this->selectAll($sql);
        } catch (Exception $e) {
            error_log('UsuarioModel::getUsuarios() -> ' . $e);
            return response::estado500($e);
        }
    }
    public function getUsuario(int $id)
    {
        $sql = "SELECT * FROM usuarios WHERE id_usuario =$id AND estado = 1";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            error_log('UsuarioModel::getUsuario() -> ' . $e);
            return response::estado500($e);
        }
    }
    public function createUsuario(array $usuario)
    {
        $requiredFields = [ 'nombre', 'apellido', 'direccion', 'telefono', 'correo', 'password', 'rol_id'];
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $usuario)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }
        $sql = "SELECT * FROM usuarios WHERE correo = :correo AND estado = 1";
        $params = [
    
            ':correo' => $usuario['correo']
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        }
        $sql = "INSERT INTO usuarios ( nombre, apellido, direccion, telefono, correo, password, rol_id, foto) VALUES (:nombre, :apellido, :direccion, :telefono, :correo, :password, :rol_id, :foto)";
        $params = [
     
            ':nombre' => $usuario['nombre'],
            ':apellido' => $usuario['apellido'],
            ':direccion' => $usuario['direccion'],
            ':telefono' => $usuario['telefono'],
            ':correo' => $usuario['correo'],
            ':password' => seguridad::createPassword($usuario['password']),
            ':rol_id' => $usuario['rol_id'],
            ':foto' => $usuario['foto']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log("usuarioModel::createUsuario() -> " . $e);
            return response::estado500();
        }
    }
    public function updateUsuario(array $usuario)
    {
        $requiredFields = ['nombre', 'apellido', 'direccion', 'telefono', 'rol_id', 'id_usuario'];
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $usuario)) {
                return response::estado400('El campo ' . $field . ' es requerido');
            }
        }
        if ($usuario['foto'] == null || $usuario['foto'] == '') {
            $usuario['foto'] = 'default.jpg';
        }


        $sql = "SELECT * FROM usuarios WHERE nombre = :nombre AND apellido = :apellido AND direccion = :direccion AND telefono = :telefono AND rol_id = :rol_id AND foto = :foto";
        $params = [
            'nombre' => $usuario['nombre'],
            'apellido' => $usuario['apellido'],
            'direccion' => $usuario['direccion'],
            'telefono' => $usuario['telefono'],
            'rol_id' => $usuario['rol_id'],
            'foto' => $usuario['foto']
        ];
        $existe = $this->select($sql, $params);
        if (!empty($existe)) {
            return "existe";
        }
        $sql = "UPDATE usuarios SET  nombre = :nombre, apellido = :apellido, direccion = :direccion, telefono = :telefono, rol_id = :rol_id, foto = :foto, fecha_mod = now() WHERE id_usuario = :id_usuario";
        $params = [
            ':nombre' => $usuario['nombre'],
            ':apellido' => $usuario['apellido'],
            ':direccion' => $usuario['direccion'],
            ':telefono' => $usuario['telefono'],
            ':rol_id' => $usuario['rol_id'],
            ':foto' => $usuario['foto'],
            ':id_usuario' => $usuario['id_usuario']
        ];
        try {
            $result = $this->save($sql, $params);
            return $result == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log("usuarioModel::updateUsuario() -> " . $e);
            return response::estado500();
        }
    }
    public function deleteUsuario(int $id)
    {
        $sql = "UPDATE usuarios SET estado = 0, fecha_baja = now() WHERE id_usuario = :id";
        $params = [':id' => $id];
        try {
            $data = $this->save($sql, $params);
            return $data == 1 ? "ok" : "error";
        } catch (Exception $e) {
            error_log('UsuarioModel::deleteUsuario() -> ' . $e);
            return response::estado500($e);
        }
    }
}
