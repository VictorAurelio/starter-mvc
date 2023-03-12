<?php
session_start();

require 'Core/config.php';

define('ROOT', 'https://localhost/mvcscratch/App/');
define("ENVIRONMENT", "development");
define("JWT_SECRET_KEY", "!A@mda!@$%sMAao28man8o");

require_once __DIR__ . '/../vendor/autoload.php';

$core = new \App\Core\Core();
$core->start();








