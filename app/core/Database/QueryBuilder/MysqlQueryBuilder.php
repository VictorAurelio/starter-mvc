<?php

namespace App\Core\Database\QueryBuilder;

use App\Core\Database\Connection\MysqlConnection;

class MysqlQueryBuilder extends QueryBuilder
{
    protected MysqlConnection $connection;

    public function __construct(MysqlConnection $connection)
    {
        $this->connection = $connection;
    }
    public function whereId(int $id): self
    {
        $this->key['conditions']['id'] = $id;
        return $this;
    }
}
