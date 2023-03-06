<?php
session_abort();

require __DIR__ . '/../app/core/config.php';
require __DIR__ . '/../vendor/autoloader.php';
require_once '../app/core/Core.php';

use App\Core\Core;

$core = new Core();
$core->start();









