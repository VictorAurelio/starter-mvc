<?php

namespace App\Core;

use App\Core\Database\DatabaseHandler;
use App\Core\Database\Connection\ConnectionInterface;

class Database
{
    private static ?ConnectionInterface $connection = null;

    private function __construct()
    {
        // Impede a criação de instâncias externamente
    }

    public static function getConnection(): ConnectionInterface
    {
        if (self::$connection === null) {
            self::$connection = DatabaseHandler::createConnection();
        }

        return self::$connection;
    }
}
