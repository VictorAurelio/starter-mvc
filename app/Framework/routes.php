<?php

use App\Framework\Routing\Route;

$routes = [];

$routes = [
    'user/profile/{id}' => 'user/profile/:id',
    'user/profile' => 'user/selfProfile'
];

$route = new Route();
$route->setRoutes($routes);
