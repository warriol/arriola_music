<?php
/**
 * ARCHIVO: index.php
 * Función: Punto de entrada principal. Orquesta la carga de plantillas y vistas.
 */

date_default_timezone_set('America/Argentina/Buenos_Aires');

$fechaLanzamiento = strtotime('2026-01-11 00:00:00');
$ahora = time();

$v = "1.2.20";

if ($ahora < $fechaLanzamiento) {
    include 'contador.php';
    exit;
}

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
    <link rel="stylesheet" href="frontend/assets/css/main.css?v=<?php echo $v; ?>">
    <style>
        :root {
            /* Definimos las rutas de los fondos con la versión de PHP */
            --bg-inicio: url('media/img/img_01.jpg?v=<?php echo $v; ?>');
            --bg-tour: url('media/img/img_03.jpg?v=<?php echo $v; ?>');
            --bg-discografia: url('media/img/img_04.jpg?v=<?php echo $v; ?>');
            --bg-multimedia: url('media/img/img_05.jpg?v=<?php echo $v; ?>');
            --bg-redes: url('media/img/img_06.jpg?v=<?php echo $v; ?>');
        }

        /* Aplicamos las variables a las secciones correspondientes */
        #s1 { background-image: var(--bg-inicio); }
        #s3 { background-image: var(--bg-tour); }
        #s4 { background-image: var(--bg-discografia); }
        #s5 { background-image: var(--bg-multimedia); }
        #s6 { background-image: var(--bg-redes); }

        /* Aseguramos que el fondo cubra bien y no se repita */
        section {
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
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