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

    public function listarAlbumes() {
        header('Content-Type: application/json');
        $soloVisibles = !(isset($_GET['admin']) && $_GET['admin'] === 'true');
        $datos = $this->modelo->getAlbumes($soloVisibles);
        echo json_encode(["status" => "success", "data" => $datos]);
    }

    public function guardarAlbum() {
        header('Content-Type: application/json');

        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';

        if (empty($nombre)) {
            echo json_encode(["status" => "error", "message" => "El nombre del álbum es obligatorio"]);
            return;
        }

        if ($this->modelo->crearAlbum($nombre, $descripcion)) {
            echo json_encode(["status" => "success", "message" => "Álbum creado exitosamente"]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo crear el álbum"]);
        }
    }

    public function listarFotos() {
        header('Content-Type: application/json');
        $albumId = $_GET['album_id'] ?? null;
        if (!$albumId) {
            echo json_encode(["status" => "error", "message" => "ID de álbum requerido"]);
            return;
        }
        $soloVisibles = !(isset($_GET['admin']) && $_GET['admin'] === 'true');
        $datos = $this->modelo->listarPorAlbum($albumId, $soloVisibles);
        echo json_encode(["status" => "success", "data" => $datos]);
    }

    public function guardar() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            return;
        }

        $albumId = $_POST['album_id'] ?? null;
        if (!$albumId) {
            echo json_encode(["status" => "error", "message" => "Debe seleccionar un álbum"]);
            return;
        }

        if (!isset($_FILES['imagenes']) || empty($_FILES['imagenes']['name'][0])) {
            echo json_encode(["status" => "error", "message" => "No se detectaron archivos"]);
            return;
        }

        $archivos = $_FILES['imagenes'];
        $exitos = 0;
        $dirDestino = __DIR__ . "/../../media/galeria/";

        for ($i = 0; $i < count($archivos['name']); $i++) {
            if ($archivos['error'][$i] !== UPLOAD_ERR_OK) continue;

            $ext = strtolower(pathinfo($archivos['name'][$i], PATHINFO_EXTENSION));
            $nuevoNombre = "pol_" . time() . "_" . $i . "_" . bin2hex(random_bytes(2)) . "." . $ext;
            $rutaCompleta = $dirDestino . $nuevoNombre;
            $tmpPath = $archivos['tmp_name'][$i];
            $filesize = $archivos['size'][$i];

            // OPTIMIZACIÓN: Si pesa más de 1MB (1,048,576 bytes)
            if ($filesize > 1048576) {
                $this->optimizarImagen($tmpPath, $rutaCompleta, $ext);
            } else {
                move_uploaded_file($tmpPath, $rutaCompleta);
            }

            $datos = [
                'album_id' => $albumId,
                'url_imagen' => $nuevoNombre,
                'pie_de_foto' => !empty($_POST['pie_de_foto']) ? $_POST['pie_de_foto'] : pathinfo($archivos['name'][$i], PATHINFO_FILENAME),
                'visible' => 1
            ];

            if ($this->modelo->crear($datos)) $exitos++;
        }

        echo json_encode(["status" => "success", "message" => "Se han sintonizado $exitos imágenes correctamente."]);
    }

    private function optimizarImagen($origen, $destino, $ext) {
        list($ancho, $alto) = getimagesize($origen);

        $maxDimension = 1600; // Límite de resolución para web
        $nuevoAncho = $ancho;
        $nuevoAlto = $alto;

        if ($ancho > $maxDimension || $alto > $maxDimension) {
            if ($ancho > $alto) {
                $nuevoAncho = $maxDimension;
                $nuevoAlto = floor($alto * ($maxDimension / $ancho));
            } else {
                $nuevoAlto = $maxDimension;
                $nuevoAncho = floor($ancho * ($maxDimension / $alto));
            }
        }

        $imgResized = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

        // Carga según extensión
        switch ($ext) {
            case 'jpg': case 'jpeg': $imgSource = imagecreatefromjpeg($origen); break;
            case 'png':
                $imgSource = imagecreatefrompng($origen);
                imagealphablending($imgResized, false);
                imagesavealpha($imgResized, true);
                break;
            case 'webp': $imgSource = imagecreatefromwebp($origen); break;
            default: return move_uploaded_file($origen, $destino);
        }

        imagecopyresampled($imgResized, $imgSource, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

        // Guardado con compresión (75%)
        if ($ext == 'png') {
            imagepng($imgResized, $destino, 7);
        } elseif ($ext == 'webp') {
            imagewebp($imgResized, $destino, 75);
        } else {
            imagejpeg($imgResized, $destino, 75);
        }

        imagedestroy($imgResized);
        imagedestroy($imgSource);
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