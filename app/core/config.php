<?php
namespace App\Core;
class config {
    public function environmentType() {
        if(ENVIRONMENT === 'development') {
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

