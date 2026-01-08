<?php
/**
 * ARCHIVO: index.php
 * Función: Punto de entrada principal. Orquesta la carga de plantillas y vistas.
 */

// Establecemos la zona horaria del artista (Argentina/Buenos Aires)
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Fecha de lanzamiento: 11 de Enero a las 00:00
$fechaLanzamiento = strtotime('2026-01-11 00:00:00');
$ahora = time();

// Si aún no es la hora, cargamos el contador y detenemos el script
if ($ahora < $fechaLanzamiento) {
    include 'contador.php';
    exit;
}

// --- Si ya es la fecha, continúa la carga normal del sitio ---

require_once 'backend/autoload.php';

try {
    $configModel = new Configuracion();
    $ajustes = $configModel->obtenerTodos();
} catch (Exception $e) {
    $ajustes = [];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    include_once 'frontend/templates/meta.php';
    ?>
    <link rel="icon" href="media/img/favicon.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="frontend/assets/css/main.css">
</head>

<body>

    <?php
    include_once 'frontend/templates/header.php';
    ?>

    <main>
        <?php
        include_once 'frontend/vistas/inicio.php';
        include_once 'frontend/vistas/galeria.php';
        include_once 'frontend/vistas/tour.php';
        include_once 'frontend/vistas/discografia.php';
        include_once 'frontend/vistas/multimedia.php';
        include_once 'frontend/vistas/redes.php';
        ?>
    </main>

    <?php
    include_once 'frontend/templates/footer.php';
    ?>

</body>

</html>