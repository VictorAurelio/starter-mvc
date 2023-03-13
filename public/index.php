<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Core;
use App\Core\Config;
use App\Core\Routing\Router;

(new Core(new Config(), new Router()))->start();
