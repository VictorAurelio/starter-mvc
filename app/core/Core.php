<?php

namespace App\Core;

use App\Controllers\HomeController;
use App\Controllers\ErrorHandlerController;

class Core {
    public function start() {
        $url = '/';

        if(isset($_GET['url'])) {
            $url .= $_GET['url'];
        }

        $parameters = [];        

        if(!empty($url) && $url != '/') {
            $url = explode('/', $url);
            array_shift($url);
            
            $currentController = ucfirst($url[0]).'Controller';
            array_shift($url);

            $currentAction = (isset($url[0]) && !empty($url[0])) ? $url[0]  : 'index';
            array_shift($url);

            if(count($url) > 0) {
                $parameters = $url;
            }

        }else {
            $currentController = 'HomeController';
            $currentAction = 'index';
        }

        // Verify if the controller actually exists and if not, redirect to 404 page
        if (class_exists($currentController)) {
            $controller = new $currentController();
            if (method_exists($controller, $currentAction)) {
                call_user_func_array(array($controller, $currentAction), $parameters);
            } else {
                $currentController = new ErrorHandlerController;
                $currentAction = 'pageNotFound';
                $parameters = [];
                $controller = new $currentController();
                call_user_func_array(array($controller, $currentAction), $parameters);
            }
        } else {
            // $currentController = new ErrorHandlerController();
            // $currentAction = 'pageNotFound';
            $parameters = [];
            // $controller = new $currentController();
            call_user_func_array(array(new ErrorHandlerController(), $currentAction), $parameters);
        }

    }
}