<?php

/**
 * Modelo Usuario
 * Gestiona la tabla 'usuarios' para el acceso administrativo.
 */
class Usuario extends Config
{

    public function __construct()
    {
        parent::__construct();
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
     * Actualiza la fecha del último acceso.
     */
    public function registrarLogin($id)
    {
        $sql = "UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}