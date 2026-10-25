<?php

// Router for tests/opcache/run.sh (PHP built-in server with opcache enabled).
spl_autoload_register(static function (string $class): void {
    $file = dirname(__DIR__, 2) . '/lib/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

$class = $_GET['class'] ?? '';
if (($_GET['mode'] ?? '') === 'child') {
    // Autoload the response model before its parent request model
    echo class_exists($class) ? $class : 'missing';
    return;
}

$request = new $class();
echo get_class($request->getResponseModel());
