<?php

namespace App\Core;

use App\Http\Controllers\Error\ErrorHandlerController;
use App\Http\Controllers\HomeController;
use App\Core\Routing\Router;
use App\Core\Config;


class Core
{
    private ErrorHandlerController $errorController;
    private HomeController $homeController;
    private Config $config;
    private Router $router;
    public function __construct(Config $config, Router $router)
    {
        $this->router = $router;
        $this->config = $config;
        $this->config->constants();
        $this->config->environmentType();
    }

    public function start()
    {
        $this->config->configureCors();
        $this->errorController = new ErrorHandlerController();
        $this->homeController = new HomeController();
        $parameters = [];
        $url = '/';

        if (isset($_GET['url'])) {
            $url .= $_GET['url'];
        }
        
        $this->router->loadRoutes('routes.php');
        $url = $this->router->checkRoutes($url);

        // var_dump($url);
        if (!empty($url) && $url != '/') {
            $url = explode('/', $url);
            array_shift($url);

            $currentController = match (true) {
                $url[0] === 'home' => "\App\Http\Controllers\HomeController",
                default => "\\App\\Http\\Controllers\\" . ucfirst($url[0]) . "\\" . (ucfirst($url[0]) . 'Controller'),
            };
            array_shift($url);

            $currentAction = (isset($url[0]) && !empty($url[0])) ? $url[0]  : DEFAULT_ACTION;
            array_shift($url);

            if (count($url) > 0) {
                $parameters = $url;
            }

        } else {
            $currentController = $this->homeController;
            $currentAction = DEFAULT_ACTION;
            $controller = new $currentController();
            call_user_func(array($controller, $currentAction), $parameters);
            return;
        }
        // Verify if the controller actually exists and if not, redirect to 404 page
        if (class_exists($currentController)) {
            $controller = new $currentController();
            if (method_exists($controller, $currentAction)) {
                call_user_func_array(array($controller, $currentAction), $parameters);
            } else {
                $this->errorController->invalidParameters();
            }
        } else {
            $this->errorController->pageNotFound();
        }
    }
}
