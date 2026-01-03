<?php
/**
 * ARCHIVO: backend/index.php
 * Descripción: Puerta de entrada oculta para el backend. Maneja ruteo entidad/acción.
 */

require_once 'autoload.php';

$method = $_SERVER['REQUEST_METHOD'];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

$parts = explode('/', $uri);

$key = array_search('backend', $parts);

if ($key === false) {
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
    echo "Frecuencia no encontrada: No se pudo cargar la clase '$controllerName'.";
}