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

    public function listar() {
        try {
            $sql = "SELECT id, username, email, ultimo_login FROM usuarios ORDER BY username ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->debug("DB_ERROR_LIST_USERS", $e->getMessage());
            return [];
        }
    }

    public function actualizarPassword($username, $newPasswordHash) {
        try {
            $sql = "UPDATE usuarios SET password = :pass WHERE username = :user";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([
                ':pass' => $newPasswordHash,
                ':user' => $username
            ]);
        } catch (PDOException $e) {
            $this->debug("DB_ERROR_RESET_PASS", $e->getMessage());
            return false;
        }
    }

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

    public function eliminar($username) {
        // Protección: No permitir eliminar al administrador principal
        if ($username === 'admin') return false;

        try {
            $sql = "DELETE FROM usuarios WHERE username = :user";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([':user' => $username]);
        } catch (PDOException $e) {
            $this->debug("DB_ERROR_DELETE_USER", $e->getMessage());
            return false;
        }
    }

    public function registrarLogin($id)
    {
        $sql = "UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}