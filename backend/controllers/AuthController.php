<?php

/**
 * ARCHIVO: backend/controllers/AuthController.php
 * Descripción: Gestiona el inicio de sesión comparando hashes sha512.
 */

class AuthController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Usuario();
    }

    public function login() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        $user = $_POST['username'] ?? '';
        $passHash = $_POST['password'] ?? '';

        $usuarioData = $this->modelo->buscarPorUsername($user);

        if ($usuarioData && $passHash === $usuarioData['password']) {

            \class\Session::set('user_id', $usuarioData['id']);
            \class\Session::set('username', $usuarioData['username']);

            $token = hash_hmac('sha256', $usuarioData['id'] . $usuarioData['username'], $this->modelo->getSecretKey());
            \class\Session::set('auth_token', $token);

            $this->modelo->registrarLogin($usuarioData['id']);

            echo json_encode([
                "status" => "success",
                "message" => "Sintonía establecida",
                "redirect" => "dashboard.php"
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
        }
    }

    public function logout() {
        \class\Session::destroy();
        header('Location: ../../frontend/admin/login.php');
    }
}