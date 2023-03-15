<?php

namespace App\Core\Database\Connection;

use App\Core\Database\QueryBuilder\QueryBuilder;
use PDO;

interface ConnectionInterface
{
    public function pdo(): PDO;
    public function query(): QueryBuilder;
}