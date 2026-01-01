<?php
/**
 * ARCHIVO: backend/controllers/UsuarioController.php
 * Descripción: Gestiona las peticiones de administración de usuarios.
 */

class UsuarioController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Usuario();
    }

    public function listar() {
        header('Content-Type: application/json');

        $datos = $this->modelo->listar();

        // Es vital que no haya ningún "echo" o espacio en blanco antes de esto
        echo json_encode([
            "status" => "success",
            "data" => $datos
        ]);
    }

    public function guardar() {
        header('Content-Type: application/json');

        $user = $_POST['username'] ?? '';
        $pass = $_POST['password'] ?? '';
        $email = $_POST['email'] ?? '';

        if (empty($user) || empty($pass)) {
            echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios"]);
            return;
        }

        if ($this->modelo->crear($user, $pass, $email)) {
            echo json_encode(["status" => "success", "message" => "Operador creado"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al crear usuario"]);
        }
    }

    public function resetear() {
        header('Content-Type: application/json');

        $user = $_POST['username'] ?? '';
        $pass = $_POST['password'] ?? ''; // Nuevo hash SHA512 enviado desde el cliente

        if (empty($user) || empty($pass)) {
            echo json_encode(["status" => "error", "message" => "Datos incompletos para el reset"]);
            return;
        }

        // Llamamos al modelo para actualizar la contraseña del usuario
        if ($this->modelo->actualizarPassword($user, $pass)) {
            echo json_encode(["status" => "success", "message" => "Contraseña restablecida correctamente"]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo actualizar la contraseña"]);
        }
    }

    public function borrar() {
        header('Content-Type: application/json');

        $uri = $_SERVER['REQUEST_URI'];
        $parts = explode('/', trim($uri, '/'));
        $username = end($parts);

        if ($this->modelo->eliminar($username)) {
            echo json_encode(["status" => "success", "message" => "Usuario eliminado"]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo eliminar"]);
        }
    }
}