<?php
/**
 * ARCHIVO: index.php
 * Función: Punto de entrada principal. Orquesta la carga de plantillas y vistas.
 */

// Cargamos el motor del backend (Autoload y Configuración)
// Usamos la ruta hacia el archivo de autoload que definimos
require_once 'backend/autoload.php';

/**
 * Inicializamos la configuración global.
 * Esto nos permitirá usar los ajustes (como el nombre del sitio) en los templates.
 */
try {
    $configModel = new Configuracion();
    $ajustes = $configModel->obtenerTodos();
} catch (Exception $e) {
    // En caso de error de conexión, podrías mostrar un mensaje sutil
    $ajustes = [];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Presencia digital de Jose Luis Arriola, músico y compositor argentino. Su discografía, tour, redes y más.">
    <meta name="keywords" content="Jose Luis Arriola, músico, compositor, argentino, discografía, tour, redes">
    <meta name="author" content="Wilson Denis Arriola">
    <title><?php echo $ajustes['nombre_sitio'] ?? 'Jose Luis Arriola - Sintonía Artística'; ?></title>
    <!-- favicon -->
    <link rel="icon" href="media/img/favicon.png" type="image/x-icon">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Estilos Personalizados Extraídos -->
    <link rel="stylesheet" href="frontend/assets/css/main.css">
</head>

<body>

    <?php
    // Cargamos el gabinete de la radio (Header y Dial)
    include_once 'frontend/templates/header.php';
    ?>

    <main>
        <?php
        /**
         * En esta etapa estática cargamos los archivos de vista directamente.
         * Más adelante, PHP decidirá qué cargar basándose en la BD.
         */
        include_once 'frontend/vistas/inicio.php';
        include_once 'frontend/vistas/galeria.php';
        include_once 'frontend/vistas/tour.php';
        include_once 'frontend/vistas/discografia.php';
        include_once 'frontend/vistas/multimedia.php';
        include_once 'frontend/vistas/redes.php';
        ?>
    </main>

    <?php
    // Cargamos la base de madera y scripts
    include_once 'frontend/templates/footer.php';
    ?>

</body>

</html>