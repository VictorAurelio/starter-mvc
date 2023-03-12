<?php

namespace App\Core\Database\Connection;

use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use InvalidArgumentException;
use Pdo;

class MysqlConnection extends Connection
{
    private Pdo $pdo;
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

        $this->pdo = new Pdo("mysql:host={$host};dbname={$database}", $username, $password);
    }

    public function pdo(): Pdo
    {
        return $this->pdo;
    }

    public function query(): MysqlQueryBuilder
    {
        return new MysqlQueryBuilder($this);
    }

}