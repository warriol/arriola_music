<?php
/**
 * ARCHIVO: backend/models/Galeria.php
 * Descripción: Modelo para gestionar la tabla 'galeria'.
 */

class Galeria extends \class\Config {

    private $conexion;

    public function __construct() {
        parent::__construct();
        $this->conexion = parent::getConexion();
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * Lista las imágenes de la galería
     */
    public function listar($soloVisibles = true) {
        try {
            $sql = "SELECT * FROM galeria";
            if ($soloVisibles) {
                $sql .= " WHERE visible = 1";
            }
            $sql .= " ORDER BY orden ASC, id DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->debug("DB_ERROR_GALERIA", $e->getMessage());
            return [];
        }
    }

    /**
     * Guarda el nombre de la imagen y su pie de foto
     */
    public function crear($datos) {
        try {
            $sql = "INSERT INTO galeria (url_imagen, pie_de_foto, visible) VALUES (:url, :pie, :vis)";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([
                ':url' => $datos['url_imagen'],
                ':pie' => $datos['pie_de_foto'],
                ':vis' => $datos['visible'] ?? 1
            ]);
        } catch (PDOException $e) {
            $this->debug("DB_ERROR_INSERT", $e->getMessage());
            return false;
        }
    }

    public function eliminar($id) {
        try {
            // Primero obtenemos el nombre para borrar el archivo físico
            $sqlSelect = "SELECT url_imagen FROM galeria WHERE id = :id";
            $stmtSel = $this->conexion->prepare($sqlSelect);
            $stmtSel->execute([':id' => $id]);
            $img = $stmtSel->fetch();

            $sql = "DELETE FROM galeria WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            if ($stmt->execute([':id' => $id])) {
                return $img['url_imagen'] ?? true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}