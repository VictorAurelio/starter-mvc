<?php

namespace App\Core;

use App\Core\Database\Connection\MysqlConnection;
use App\Core\Database\DataMapper\DataMapper;
use App\Controllers\ErrorHandlerController;
use App\Controllers\HomeController;
use App\Core\Routing\Router;
use App\Core\Config;


class Core
{
    private Config $config;
    private Router $router;
    private ErrorHandlerController $errorController;
    private HomeController $homeController;
    public function __construct(Config $config, Router $router)
    {
        $this->router = $router;
        $this->config = $config;
        $this->config->constants();
        $this->config->environmentType();

        $dbConfig = [
            'host' => DB_HOST,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASS,
        ];        
        $connection = new MysqlConnection($dbConfig);

        (new DataMapper($connection));
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

        if (!empty($url) && $url != '/') {
            $url = explode('/', $url);
            array_shift($url);

            $currentController = "\\App\\Controllers\\" . (ucfirst($url[0]) . 'Controller');
            array_shift($url);
            // var_dump($currentController);
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
