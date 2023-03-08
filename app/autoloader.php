<?php
require 'Core/config.php';

define('ROOT', 'https://localhost/mvcscratch/App/');
define("ENVIROMENT", "development");

spl_autoload_register(function ($class) {
    $file = str_replace('\\', '/', $class) . '.php';
    $path = __DIR__ . '/../' . $file; // add base directory path
    if (file_exists($path)) {
        require_once $path;
        return;
    }
    // var_dump($file);
    // define('DEBUG', true);
});