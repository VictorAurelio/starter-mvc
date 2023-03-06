<?php

require 'environment.php';
// $config = [];

if(ENVIROMENT === 'development') {
    define("BASE_URL", "http://localhost/mvcscratch/public");
    define("DB_NAME", "mvc");
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASS", "");
}else {
    // define("BASE_URL", "https://www.mywebsite.com");
    define("DB_NAME", "mvc");
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASS", "");
}

// global $db;

// try {    
//     $db = new PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST, DB_USER, DB_PASS);
// } catch (PDOException $e) {
//     echo "ERROR:" . $e->getMessage();
//     exit;    
// }