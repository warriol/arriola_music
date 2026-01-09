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

    public function getAlbumes($soloVisibles = true) {
        try {
            $sql = "SELECT a.*, 
                    (SELECT url_imagen FROM galeria WHERE album_id = a.id AND visible = 1 LIMIT 1) as portada 
                    FROM albumes a";

            if ($soloVisibles) $sql .= " WHERE a.visible = 1";
            $sql .= " ORDER BY a.id DESC";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->debug("DB_ERROR_ALBUMES", $e->getMessage());
            return [];
        }
    }


    public function crearAlbum($nombre, $descripcion = '') {
        try {
            $sql = "INSERT INTO albumes (nombre, descripcion) VALUES (:nom, :des)";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([':nom' => $nombre, ':des' => $descripcion]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarPorAlbum($albumId, $soloVisibles = true) {
        try {
            $sql = "SELECT * FROM galeria WHERE album_id = :aid";
            if ($soloVisibles) $sql .= " AND visible = 1";
            $sql .= " ORDER BY orden ASC, id DESC";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([':aid' => $albumId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO galeria (album_id, url_imagen, pie_de_foto, visible) 
                    VALUES (:aid, :url, :pie, :vis)";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([
                ':aid' => $datos['album_id'],
                ':url' => $datos['url_imagen'],
                ':pie' => $datos['pie_de_foto'],
                ':vis' => $datos['visible'] ?? 1
            ]);
        } catch (PDOException $e) {
            $this->debug("DB_ERROR_GALERIA_INSERT", $e->getMessage());
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