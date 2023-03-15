<?php

namespace App\Core\Database;

use App\Core\Database\QueryBuilder\QueryBuilderFactory;
use App\Core\Database\Connection\MysqlConnection;
use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DataMapper\DataMapper;
use App\Core\Database\DAO\DAO;

class DatabaseHandler
{
    protected ConnectionInterface $connection;
    protected string $tableSchemaId;
    protected string $tableSchema;
    protected array $options;

    public function __construct(ConnectionInterface $connection, string $tableSchema, string $tableSchemaId, ?array $options = [])
    {
        $this->tableSchemaId = $tableSchemaId;
        $this->tableSchema = $tableSchema;
        $this->connection = $connection;
        $this->options = $options;
    }
    public static function createConnection(): ConnectionInterface
    {
        $dbConfig = [
            'host' => DB_HOST,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASS,
        ];

        return new MysqlConnection($dbConfig);
    }

    public function initialize(): object
    {
        $connection = self::createConnection();
        $queryBuilderFactory = new QueryBuilderFactory();
        $queryBuilder = $queryBuilderFactory->create($connection);
        $dataMapper = new DataMapper($connection);
        $baseDAO = new DAO($dataMapper, $queryBuilder, $this->tableSchema, $this->tableSchemaId, $this->options);
        return $baseDAO;
    }
}
