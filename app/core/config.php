<?php

require 'environment.php';
// $config = [];

trait Config {
    public function environmentType() {
        if(ENVIROMENT === 'development') {
            define("BASE_URL", "http://localhost/mvcscratch/public");
            define("DB_NAME", "mvc");
            define("DB_HOST", "localhost");
            define("DB_USER", "root");
            define("DB_PASS", "");
        }else {
            // define("BASE_URL", "https://www.mywebsite.com");
            // define("DB_NAME", "mvc");
            // define("DB_HOST", "localhost");
            // define("DB_USER", "root");
            // define("DB_PASS", "");
        }
    }
}

