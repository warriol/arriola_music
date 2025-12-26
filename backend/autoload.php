<?php
spl_autoload_register(function ($class) {
    $classPaths = [
        __DIR__ . '/class/' . $class . '.php',
        __DIR__ . '/controllers/' . str_replace('controllers\\', '', $class) . '.php',
        __DIR__ . '/models/' . str_replace('models\\', '', $class) . '.php'
    ];

    foreach ($classPaths as $classPath) {
        //echo $classPath.'<br>';
        if (file_exists($classPath)) {
            require_once $classPath;
            break;
        }
    }
});