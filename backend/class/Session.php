<?php

namespace class;

/**
 * Clase Session
 * Maneja la seguridad de la sesión del administrador.
 */
class Session
{

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        self::start();
        return $_SESSION[$key] ?? null;
    }

    /**
     * Valida si el usuario está autenticado comprobando el token
     * generado a partir de la SECRET_KEY.
     */
    public static function check($secretKey): bool
    {
        self::start();
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['auth_token'])) {
            return false;
        }

        // Validamos que el token coincida con la firma esperada
        $expectedToken = hash_hmac('sha256', $_SESSION['user_id'] . $_SESSION['username'], $secretKey);
        return hash_equals($expectedToken, $_SESSION['auth_token']);
    }

    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }
}