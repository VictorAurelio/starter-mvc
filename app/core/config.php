<?php

require 'environment.php';

$config = [];

if(ENVIROMENT === 'development') {
    define("BASE_URL", "http://localhost/mvcscratch/public");
    $config['db_name'] = 'mvc';
    $config['db_host'] = 'localhost';
    $config['db_user'] = 'root';
    $config['db_pass'] = '';
}else {
    // define("BASE_URL", "https://www.mywebsite.com");
    // $config['db_name'] = 'firsttry';
    // $config['db_host'] = 'localhost';
    // $config['db_user'] = 'root';
    // $config['db_pass'] = '';
}

global $db;

try {    
    $db = new PDO("mysql:dbname=".$config['db_name'].";host=".$config['db_host'], $config['db_user'], $config['db_pass']);
} catch (PDOException $e) {
    echo "ERROR:" . $e->getMessage();
    exit;    
}