<?php
/**
 * ARCHIVO: backend/models/Configuracion.php
 * Descripción: Modelo para gestionar la tabla de ajustes globales.
 */

class Configuracion extends \class\Config {

    private $conexion;

    public function __construct() {
        parent::__construct();
        $this->conexion = parent::getConexion();
    }

    public function obtenerTodos() {
        try {
            $sql = "SELECT * FROM configuracion";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll();

            $config = [];
            foreach ($resultados as $row) {
                $config[$row['clave']] = $row['valor'];
            }
            return $config;
        } catch (PDOException $e) {
            $this->debug("DB_ERROR_CONFIG", $e->getMessage());
            return [];
        }
    }

    public function actualizar($clave, $valor) {
        try {
            $sql = "INSERT INTO configuracion (clave, valor) VALUES (:clave, :valor) 
                    ON DUPLICATE KEY UPDATE valor = :valor2";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([
                ':clave' => $clave,
                ':valor' => $valor,
                ':valor2' => $valor
            ]);
        } catch (PDOException $e) {
            $this->debug("DB_ERROR_UPDATE_CONFIG", $e->getMessage());
            return false;
        }
    }
}