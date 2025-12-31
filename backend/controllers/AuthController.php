<?php

/**
 * Controlador AuthController
 * Gestiona el proceso de entrada y salida del sistema.
 */
class AuthController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new Usuario();
    }

    /**
     * Procesa el login (POST)
     */
    public function login()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        $user = $_POST['username'] ?? '';
        $pass = $_POST['password'] ?? '';

        $usuarioData = $this->modelo->buscarPorUsername($user);

        if ($usuarioData && password_verify($pass, $usuarioData['password'])) {
            // Generamos token de seguridad usando la SECRET_KEY heredada del modelo
            // (Accedemos a ella a través de una propiedad protegida si es necesario)
            // Para simplificar, usamos la Session con los datos del usuario.

            \class\Session::set('user_id', $usuarioData['id']);
            \class\Session::set('username', $usuarioData['username']);

            // Firma de seguridad del token
            $token = hash_hmac('sha256', $usuarioData['id'] . $usuarioData['username'], $this->modelo->getSecretKey());
            \class\Session::set('auth_token', $token);

            $this->modelo->registrarLogin($usuarioData['id']);

            echo json_encode([
                "status" => "success",
                "message" => "Acceso concedido",
                "redirect" => "frontend/admin/dashboard.php"
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Credenciales inválidas"]);
        }
    }

    public function logout()
    {
        \class\Session::destroy();
        header('Location: ../frontend/admin/login.php');
    }
}