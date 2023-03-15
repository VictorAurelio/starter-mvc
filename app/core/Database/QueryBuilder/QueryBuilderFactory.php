<?php

namespace App\Core\Database\QueryBuilder;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\Connection\MysqlConnection;

class QueryBuilderFactory
{
    public function create(ConnectionInterface $connection): QueryBuilderInterface
    {
        if ($connection instanceof MysqlConnection) {
            return new MysqlQueryBuilder($connection);
        }
        
        throw new \InvalidArgumentException('Unsupported connection type');
    }
}