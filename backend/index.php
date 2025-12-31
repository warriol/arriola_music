<?php
/**
 * ARCHIVO: backend/index.php
 * Descripción: Puerta de entrada oculta para el backend. Maneja ruteo entidad/acción.
 */

// Cargamos el autoload
require_once 'autoload.php';

// Verificamos el método de la petición
$method = $_SERVER['REQUEST_METHOD'];

// Obtenemos la URI y la limpiamos
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// Suponiendo que la URL es midominio.com/backend/entidad/accion
$parts = explode('/', $uri);

// Buscamos la posición de 'backend' en la URL para extraer lo que sigue
$key = array_search('backend', $parts);

if ($key === false) {
    // Si 'backend' no está en la URL, algo va mal con la petición
    header("HTTP/1.1 400 Bad Request");
    exit("Error de sintonía: URI mal formada.");
}

$entidad = $parts[$key + 1] ?? null;
$accion = $parts[$key + 2] ?? 'index';

if (!$entidad) {
    header("HTTP/1.1 404 Not Found");
    exit("Acceso restringido: Indica una frecuencia válida.");
}


$controllerName = ucfirst($entidad) . 'Controller';

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $accion)) {
        $controller->$accion();
    } else {
        echo "Acción ($accion) no sintonizada en el dial de $controllerName.";
    }
} else {
    // Si llegamos aquí, el Autoload falló o el nombre de la clase no coincide con el archivo
    echo "Frecuencia no encontrada: No se pudo cargar la clase '$controllerName'.";
}