<?php

namespace App\Core\Database\Connection;

use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Core\Config;
use InvalidArgumentException;
use PDO;

class MysqlConnection extends Connection
{
    private PDO $pdo;
    private string $database;
    public function __construct(array $config)
    {
        [
            'host' => $host,
            'database' => $database,
            'username' => $username,
            'password' => $password,
        ] = $config;

        if (empty($host) || empty($database) || empty($username)) {
            throw new InvalidArgumentException('Connection incorrectly configured');
        }
        $this->database = $database;
        $this->pdo = new PDO("mysql:host={$host};dbname={$database}", $username, $password);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    public function query(): MysqlQueryBuilder
    {
        return new MysqlQueryBuilder($this);
    }
}
