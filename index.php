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