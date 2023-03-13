<?php

namespace App\Core;

class Config
{
    public function constants()
    {        
        define('ROOT', dirname(dirname(__FILE__)) . '/App');
        define("ENVIRONMENT", "development");
        define("JWT_SECRET_KEY", "!A@mda!@$%sMAao28man8o");
    }
    public function environmentType()
    {
        if (ENVIRONMENT === 'development') {
            define("BASE_URL", "http://localhost/mvcscratch/public");
            define("DB_NAME", "mvc");
            define("DB_HOST", "localhost");
            define("DB_USER", "root");
            define("DB_PASS", "");
        } else {
            define("BASE_URL", "https://www.mywebsite.com");
            define("DB_NAME", "mvc");
            define("DB_HOST", "localhost");
            define("DB_USER", "root");
            define("DB_PASS", "");
        }
    }
}
