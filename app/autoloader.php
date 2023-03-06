<?php

spl_autoload_register(function ($class) {
    $file = str_replace('\\', '/', $class) . '.php';
    $path = __DIR__ . '/../' . $file; // add base directory path
    if (file_exists($path)) {
        require_once $path;
        return;
    }
    // var_dump($file);
});