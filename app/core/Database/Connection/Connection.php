<?php

namespace App\Core\Database\Connection;

use App\Core\Database\QueryBuilder\QueryBuilder;
use PDO;

abstract class Connection
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
