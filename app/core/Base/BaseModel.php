<?php

namespace App\Core\Base;

use App\Core\Base\Exceptions\BaseInvalidArgumentException;
use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DAO\DAO;
use App\Core\Database\DatabaseHandler;
use App\Core\Database\DataMapper\DataMapper;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;

class BaseModel
{
    protected string $tableSchema;
    protected string $tableSchemaId;
    protected ConnectionInterface $connection;
    protected DAO $dao;
    public function __construct(string $tableSchema, string $tableSchemaId, ConnectionInterface $connection)
    {
        if (empty($tableSchema || empty($tableSchemaId))) {
            throw new BaseInvalidArgumentException('These arguments are required.');
        }
        $this->tableSchema = $tableSchema;
        $this->tableSchemaId = $tableSchemaId;
        $this->connection = $connection;
        $this->dao = new DAO(
            new DataMapper($connection),
            new MysqlQueryBuilder($connection),
            $this->tableSchema,
            $this->tableSchemaId
        );
    }
    public function initialize(): object
    {
        $options = [];
        $handler = new DatabaseHandler($this->connection, $this->tableSchema, $this->tableSchemaId, $options);
        return $handler->initialize();
    }
}
