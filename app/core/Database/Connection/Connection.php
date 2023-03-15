<?php

namespace App\Core\Database\Connection;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\QueryBuilder\QueryBuilder;
use PDO;

abstract class Connection implements ConnectionInterface
{
    /**
     * Get the underlying Pdo instance for this connection
     */
    abstract public function pdo(): PDO;
    /**
     * Start a new query on this connection
     */
    abstract public function query(): QueryBuilder;
}
