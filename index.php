<?php
/**
 * ARCHIVO: index.php
 * Función: Punto de entrada principal. Orquesta la carga de plantillas y vistas.
 */

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