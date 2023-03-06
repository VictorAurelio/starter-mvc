<?php
session_start();

require('../App/autoloader.php');
// require __DIR__ . '/../App/Core/Config.php';

// require '../App/Core/Core.php';

// DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

$core = new \App\Core\Core();
$core->start();








