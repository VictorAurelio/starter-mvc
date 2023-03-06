<?php
session_start();

require_once '../App/autoloader.php';
// require __DIR__ . '/../App/Core/Config.php';

// require '../App/Core/Core.php';


$core = new \App\Core\Core();
$core->start();








