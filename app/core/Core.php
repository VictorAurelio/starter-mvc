<?php

namespace App\Core;

use App\Core\Database\Connection\MysqlConnection;
use App\Controllers\HomeController;
use App\Controllers\ErrorHandlerController;
use App\Core\Database\DataMapper\DataMapper;
use App\Core\Routing\Router;


class Core
{
    public function __construct()
    {
        $this->configureCors();
        $config = [
            'host' => DB_HOST,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASS,
        ];

        $connection = new MysqlConnection($config);
        var_dump($connection);
        $dataMapper = new DataMapper($connection);
        echo '<br><br>';
        var_dump($dataMapper);
    }
    public function configureCors()
    {
        require_once('cors.php');
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            setCorsHeaders();
            exit;
        }
    }
    public function start()
    {
        $errorController = new ErrorHandlerController();
        $homeController = new HomeController();
        $parameters = [];
        $url = '/';

        if (isset($_GET['url'])) {
            $url .= $_GET['url'];
        }

        $router = new Router($url);
        $router->loadRoutes('routes.php');
        $url = $router->checkRoutes();

        if (!empty($url) && $url != '/') {
            $url = explode('/', $url);
            array_shift($url);

            $currentController = "\\App\\Controllers\\" . (ucfirst($url[0]) . 'Controller');
            array_shift($url);
            // var_dump($currentController);
            $currentAction = (isset($url[0]) && !empty($url[0])) ? $url[0]  : 'index';
            array_shift($url);

            if (count($url) > 0) {
                $parameters = $url;
            }
        } else {
            $currentController = $homeController;
            // var_dump($currentController);
            $currentAction = 'index';
            $controller = new $currentController();
            // var_dump($controller);
            call_user_func(array($controller, $currentAction), $parameters);
            return;
        }

        // Verify if the controller actually exists and if not, redirect to 404 page
        if (class_exists($currentController)) {
            $controller = new $currentController();
            if (method_exists($controller, $currentAction)) {
                call_user_func_array(array($controller, $currentAction), $parameters);
            } else {
                $currentController = $errorController;
                $currentAction = 'pageNotFound';
                $parameters = [];
                $controller = new $currentController();
                call_user_func_array(array($controller, $currentAction), $parameters);
            }
        } else {
            $currentController = $errorController;
            $currentAction = 'pageNotFound';
            $parameters = [];
            $controller = new $currentController();
            call_user_func_array(array($controller, $currentAction), $parameters);
        }
    }
}
