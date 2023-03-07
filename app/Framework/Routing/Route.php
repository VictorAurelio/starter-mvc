<?php

namespace App\Framework\Routing;
use App\Core\Core;

class Route extends Core{
    protected $routes;
    public function __construct() {
        $this->routes = [];
    }
    public function setRoutes(array $routes) {
        $this->routes = $routes;
    }
    public function getRoutes(): array {
        return $this->routes;
    }
}