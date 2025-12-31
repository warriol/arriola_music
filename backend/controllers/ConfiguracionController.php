<?php
/**
 * ARCHIVO: backend/controllers/ConfiguracionController.php
 * Descripción: Maneja las peticiones para leer y guardar ajustes del sitio.
 */

class ConfiguracionController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Configuracion();
    }

    public function listar() {
        header('Content-Type: application/json');
        $datos = $this->modelo->obtenerTodos();
        echo json_encode(["status" => "success", "data" => $datos]);
    }

    public function guardar() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        $exito = true;
        foreach ($_POST as $clave => $valor) {
            // Limpiamos un poco el valor antes de guardar
            if (!$this->modelo->actualizar($clave, trim($valor))) {
                $exito = false;
            }
        }

        if ($exito) {
            echo json_encode(["status" => "success", "message" => "Ajustes sintonizados correctamente"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Hubo un problema al guardar algunos ajustes"]);
        }
    }
}