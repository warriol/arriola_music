<?php

/**
 * Modelo Usuario
 * Gestiona la tabla 'usuarios' para el acceso administrativo.
 */
use class\Config;
class Usuario extends Config
{
    private $conexion;

    public function __construct()
    {
        parent::__construct();
        $this->conexion = parent::getConexion();
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * Busca un usuario por su nombre de usuario.
     */
    public function buscarPorUsername($username)
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE username = :user LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([':user' => $username]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->debug("Error Login", $e->getMessage());
            return null;
        }
    }

    /**
     * Crea un nuevo usuario con la contraseña ya hasheada
     */
    public function crear($username, $passwordHash, $email = '') {
        try {
            $sql = "INSERT INTO usuarios (username, password, email) VALUES (:user, :pass, :email)";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([
                ':user' => $username,
                ':pass' => $passwordHash,
                ':email' => $email
            ]);
        } catch (PDOException $e) {
            $this->debug("Error Creando Usuario", $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza la fecha del último acceso.
     */
    public function registrarLogin($id)
    {
        $sql = "UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}