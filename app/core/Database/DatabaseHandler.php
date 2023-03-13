<?php

namespace App\Core\Database;

use App\Core\Database\QueryBuilder\QueryBuilderFactory;
use App\Core\Database\DataMapper\DataMapperFactory;
use App\Core\Database\QueryBuilder\QueryBuilder;
use App\Core\Database\Connection\Connection;
use App\Core\Database\DAO\BaseDAOFactory;

class DatabaseHandler
{
    protected Connection $connection;
    protected string $tableSchemaId;
    protected string $tableSchema;
    protected array $options;

    public function __construct(Connection $connection, string $tableSchema, string $tableSchemaId, ?array $options = [])
    {
        $this->tableSchemaId = $tableSchemaId;
        $this->tableSchema = $tableSchema;
        $this->connection = $connection;
        $this->options = $options;
    }

    public function initialize()
    {
        $dataMapperFactory = new DataMapperFactory();
        $dataMapper = $dataMapperFactory->create(Connection::class, Connection::class);
        if ($dataMapper) {
            $queryBuilderFactory = new QueryBuilderFactory();
            $queryBuilder = $queryBuilderFactory->create(QueryBuilder::class);
            if ($queryBuilder) {
                $baseDaoFactory = new BaseDAOFactory($dataMapper, $queryBuilder);
                return $baseDaoFactory->create(BaseDAO::class, $this->tableSchema, $this->tableSchemaId, $this->options);
            }
        }
    }
}
