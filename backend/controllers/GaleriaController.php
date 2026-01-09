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
            echo json_encode(["status" => "error", "message" => "Selecciona un álbum"]);
            return;
        }

        if (!isset($_FILES['imagenes']) || empty($_FILES['imagenes']['name'][0])) {
            echo json_encode(["status" => "error", "message" => "No hay archivos"]);
            return;
        }

        $archivos = $_FILES['imagenes'];
        $exitos = 0;
        $dirDestino = __DIR__ . "/../../media/galeria/";

        if (!is_dir($dirDestino)) mkdir($dirDestino, 0777, true);

        for ($i = 0; $i < count($archivos['name']); $i++) {
            if ($archivos['error'][$i] !== UPLOAD_ERR_OK) continue;

            $ext = strtolower(pathinfo($archivos['name'][$i], PATHINFO_EXTENSION));
            $nuevoNombre = "pol_" . time() . "_" . $i . "_" . bin2hex(random_bytes(2)) . "." . $ext;
            $rutaCompleta = $dirDestino . $nuevoNombre;
            $tmpPath = $archivos['tmp_name'][$i];
            $filesize = $archivos['size'][$i];

            $this->procesarImagen($tmpPath, $rutaCompleta, $ext, $filesize > 1048576);

            $datos = [
                'album_id' => $albumId,
                'url_imagen' => $nuevoNombre,
                'pie_de_foto' => !empty($_POST['pie_de_foto']) ? $_POST['pie_de_foto'] : pathinfo($archivos['name'][$i], PATHINFO_FILENAME),
                'visible' => 1
            ];

            if ($this->modelo->crear($datos)) $exitos++;
        }

        echo json_encode(["status" => "success", "message" => "Se sintonizaron $exitos imágenes correctamente."]);
    }

    private function procesarImagen($origen, $destino, $ext, $debeRedimensionar) {

        switch ($ext) {
            case 'jpg': case 'jpeg': $imgSource = @imagecreatefromjpeg($origen); break;
            case 'png': $imgSource = @imagecreatefrompng($origen); break;
            case 'webp': $imgSource = @imagecreatefromwebp($origen); break;
            default: return move_uploaded_file($origen, $destino);
        }

        if (!$imgSource) return move_uploaded_file($origen, $destino);

        if ($ext === 'jpg' || $ext === 'jpeg') {
            $exif = @exif_read_data($origen);
            if ($exif && isset($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3: $imgSource = imagerotate($imgSource, 180, 0); break;
                    case 6: $imgSource = imagerotate($imgSource, -90, 0); break;
                    case 8: $imgSource = imagerotate($imgSource, 90, 0); break;
                }
            }
        }

        $anchoOriginal = imagesx($imgSource);
        $altoOriginal = imagesy($imgSource);

        $nuevoAncho = $anchoOriginal;
        $nuevoAlto = $altoOriginal;

        if ($debeRedimensionar) {
            $maxDimension = 1600;
            if ($anchoOriginal > $maxDimension || $altoOriginal > $maxDimension) {
                if ($anchoOriginal > $altoOriginal) {
                    $nuevoAncho = $maxDimension;
                    $nuevoAlto = floor($altoOriginal * ($maxDimension / $anchoOriginal));
                } else {
                    $nuevoAlto = $maxDimension;
                    $nuevoAncho = floor($anchoOriginal * ($maxDimension / $altoOriginal));
                }
            }
        }

        $imgFinal = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

        if ($ext === 'png' || $ext === 'webp') {
            imagealphablending($imgFinal, false);
            imagesavealpha($imgFinal, true);
        }

        imagecopyresampled($imgFinal, $imgSource, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $anchoOriginal, $altoOriginal);

        switch ($ext) {
            case 'jpg': case 'jpeg': imagejpeg($imgFinal, $destino, 80); break;
            case 'png': imagepng($imgFinal, $destino, 7); break;
            case 'webp': imagewebp($imgFinal, $destino, 80); break;
        }

        imagedestroy($imgFinal);
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