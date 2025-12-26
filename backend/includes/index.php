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

// Como este archivo está en /backend, omitimos esa parte de la URI
// Buscamos las partes después de 'backend'
$key = array_search('backend', $parts);
$entidad = $parts[$key + 1] ?? null;
$accion = $parts[$key + 2] ?? 'index';

if (!$entidad) {
    // Si no hay entidad, mostramos el formulario de login o error
    // Por seguridad, si no conocen la URL exacta, podemos enviar un 404 falso
    header("HTTP/1.1 404 Not Found");
    exit("Acceso restringido.");
}

/**
 * Lógica de Ruteo Dinámico
 * Se instancia el controlador correspondiente y se ejecuta la acción.
 */
$controllerName = ucfirst($entidad) . 'Controller';

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $accion)) {
        $controller->$accion();
    } else {
        echo "Acción no sintonizada.";
    }
} else {
    echo "Frecuencia no encontrada.";
}