<?php
/**
 * ARCHIVO: backend/controllers/GaleriaController.php
 * Descripción: Maneja la subida (individual o masiva) de archivos y registro en BD.
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

    public function guardar() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        if (!isset($_FILES['imagenes']) || empty($_FILES['imagenes']['name'][0])) {
            echo json_encode(["status" => "error", "message" => "No se detectaron archivos para sintonizar."]);
            return;
        }

        $archivos = $_FILES['imagenes'];
        $total = count($archivos['name']);
        $exitos = 0;
        $errores = [];

        $dirDestino = __DIR__ . "/../../media/galeria/";
        if (!is_dir($dirDestino)) {
            mkdir($dirDestino, 0777, true);
        }

        for ($i = 0; $i < $total; $i++) {
            if ($archivos['error'][$i] !== UPLOAD_ERR_OK) continue;

            $nombreOriginal = $archivos['name'][$i];
            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

            $nuevoNombre = "pol_" . time() . "_" . $i . "_" . bin2hex(random_bytes(2)) . "." . $extension;
            $rutaCompleta = $dirDestino . $nuevoNombre;

            if (move_uploaded_file($archivos['tmp_name'][$i], $rutaCompleta)) {

                $pieDeFoto = !empty($_POST['pie_de_foto'])
                    ? $_POST['pie_de_foto']
                    : pathinfo($nombreOriginal, PATHINFO_FILENAME);

                $datos = [
                    'url_imagen' => $nuevoNombre,
                    'pie_de_foto' => $pieDeFoto,
                    'visible' => $_POST['visible'] ?? 1
                ];

                if ($this->modelo->crear($datos)) {
                    $exitos++;
                } else {
                    $errores[] = "Error en BD con: " . $nombreOriginal;
                }
            } else {
                $errores[] = "Fallo al mover: " . $nombreOriginal;
            }
        }

        if ($exitos > 0) {
            echo json_encode([
                "status" => "success",
                "message" => "Se han sintonizado $exitos imágenes correctamente.",
                "errores" => $errores
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No se pudo subir ninguna imagen al dial.",
                "detalles" => $errores
            ]);
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
                $ruta = __DIR__ . "/../../media/galeria/" . $nombreArchivo;
                if (file_exists($ruta)) unlink($ruta);
                echo json_encode(["status" => "success"]);
                return;
            }
        }
        echo json_encode(["status" => "error", "message" => "No se pudo borrar la frecuencia visual."]);
    }
}