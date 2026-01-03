<?php
/**
 * ARCHIVO: backend/controllers/TourController.php
 * Descripción: Controlador para manejar las peticiones relacionadas con el Tour.
 */


class TourController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Tour();
    }

    public function index() {
        $this->listar();
    }

    public function listar() {
        $soloVisibles = !(isset($_GET['admin']) && $_GET['admin'] === 'true');

        $resultado = $this->modelo->listar($soloVisibles);

        header('Content-Type: application/json');

        if (isset($resultado['error'])) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Error al sintonizar los datos",
                "error" => $resultado['error']
            ]);
            return;
        }

        // Respuesta estandarizada
        echo json_encode([
            "status" => "success",
            "count" => count($resultado),
            "data" => $resultado,
            "message" => (count($resultado) === 0) ? "No hay eventos programados en este momento." : "Eventos sintonizados correctamente."
        ]);
    }

    public function obtener() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(["status" => "error", "message" => "ID no proporcionado"]);
            return;
        }

        $evento = $this->modelo->buscarPorId($id);
        echo json_encode(["status" => "success", "data" => $evento]);
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resultado = $this->modelo->crear($_POST);

            header('Content-Type: application/json');
            echo json_encode(['success' => $resultado]);
        }
    }

    public function borrar() {
        $uri = $_SERVER['REQUEST_URI'];
        $parts = explode('/', trim($uri, '/'));
        $id = end($parts);

        if (is_numeric($id)) {
            $resultado = $this->modelo->eliminar($id);
            header('Content-Type: application/json');
            echo json_encode(['success' => $resultado]);
        }
    }
}