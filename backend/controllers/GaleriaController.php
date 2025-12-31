<?php
/**
 * ARCHIVO: backend/controllers/GaleriaController.php
 * Descripción: Maneja la lógica de subida de archivos y registro en BD.
 */

class GaleriaController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Galeria();
    }

    public function listar() {
        header('Content-Type: application/json');
        $adminMode = isset($_GET['admin']) && $_GET['admin'] === 'true';
        $datos = $this->modelo->listar(!$adminMode);
        echo json_encode(["status" => "success", "data" => $datos]);
    }

    /**
     * Procesa la subida de la imagen y guarda en BD
     */
    public function guardar() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(["status" => "error", "message" => "Error al recibir el archivo"]);
            return;
        }

        // 1. Lógica de Renombrado
        $nombreOriginal = $_FILES['imagen']['name'];
        $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

        // Creamos un nombre corto y único: pol_ (de polaroid) + timestamp + random
        $nuevoNombre = "pol_" . time() . "_" . bin2hex(random_bytes(2)) . "." . $extension;

        // 2. Ruta de destino (carpeta media/galeria en la raíz)
        $dirDestino = __DIR__ . "/../../media/galeria/";

        if (!is_dir($dirDestino)) {
            mkdir($dirDestino, 0777, true);
        }

        $rutaCompleta = $dirDestino . $nuevoNombre;

        // 3. Mover archivo
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            // 4. Guardar solo el nombre en la base de datos
            $datos = [
                'url_imagen' => $nuevoNombre,
                'pie_de_foto' => $_POST['pie_de_foto'] ?? '',
                'visible' => $_POST['visible'] ?? 1
            ];

            if ($this->modelo->crear($datos)) {
                echo json_encode(["status" => "success", "message" => "Imagen sintonizada correctamente"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al registrar en la base de datos"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo guardar el archivo físico"]);
        }
    }

    public function borrar() {
        header('Content-Type: application/json');
        $uri = $_SERVER['REQUEST_URI'];
        $parts = explode('/', trim($uri, '/'));
        $id = end($parts);

        if (is_numeric($id)) {
            $nombreArchivo = $this->modelo->eliminar($id);
            if ($nombreArchivo) {
                // Borrar archivo físico si existe
                $ruta = __DIR__ . "/../../media/galeria/" . $nombreArchivo;
                if (file_exists($ruta)) unlink($ruta);

                echo json_encode(["status" => "success"]);
                return;
            }
        }
        echo json_encode(["status" => "error"]);
    }
}