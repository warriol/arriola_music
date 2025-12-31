<?php
/**
 * ARCHIVO: backend/models/Tour.php
 * Descripción: Modelo para gestionar la tabla 'tour'.
 */
use class\Config;

class Tour extends Config {

    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = parent::getConexion();
    }

    public function listar($soloVisibles = true) {
        $sql = "SELECT * FROM tour";
        if ($soloVisibles) {
            $sql .= " WHERE visible = 1";
        }
        $sql .= " ORDER BY fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        parent::debug("SQL Ejecutada", $sql);
        parent::debug("Eventos encontrados", $stmt->rowCount());

        return $stmt->fetchAll();
    }

    public function crear($datos) {
        $sql = "INSERT INTO tour (fecha, lugar, descripcion, direccion, url_tickets, hashtag, visible) 
                VALUES (:fecha, :lugar, :descripcion, :direccion, :url_tickets, :hashtag, :visible)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':fecha'       => $datos['fecha'],
            ':lugar'       => $datos['lugar'],
            ':descripcion' => $datos['descripcion'],
            ':direccion'   => $datos['direccion'],
            ':url_tickets' => $datos['url_tickets'],
            ':hashtag'     => $datos['hashtag'],
            ':visible'     => $datos['visible'] ?? 1
        ]);
    }

    public function actualizar($id, $datos) {
        $sql = "UPDATE tour SET 
                fecha = :fecha, lugar = :lugar, descripcion = :descripcion, 
                direccion = :direccion, url_tickets = :url_tickets, 
                hashtag = :hashtag, visible = :visible 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $datos['id'] = $id;
        return $stmt->execute($datos);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM tour WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

}