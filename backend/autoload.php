<?php
/**
 * ARCHIVO: backend/autoload.php
 * Descripción: Registra la carga automática de clases buscando en los directorios internos.
 */

spl_autoload_register(function ($class) {
    $directories = [
        'class',
        'controllers',
        'models'
    ];

    $parts = explode('\\', $class);
    $className = end($parts);

    foreach ($directories as $directory) {
        $file = __DIR__ . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $className . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});