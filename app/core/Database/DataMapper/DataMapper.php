<?php

namespace App\Core\Database\DataMapper;

use App\Core\Database\DataMapper\Exception\DataMapperException;
use App\Core\Database\Connection\Connection;
use PDOStatement;
use Throwable;
use PDO;

/**
 * Summary of DataMapper
 */
class DataMapper implements DataMapperInterface
{
    protected Connection $connection;
    private PDOStatement $statement;
    /**
     * Summary of __construct
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    /**
     * Summary of isEmpty
     * @param string|null $errorMessage
     * @throws DataMapperException
     * @param mixed $value
     * @return void
     */
    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            throw new DataMapperException($errorMessage);
        }
    }
    /**
     * Summary of isArray
     * @throws DataMapperException
     * @param array $value
     * @return void
     */
    private function isArray(array $value)
    {
        if (!is_array($value)) {
            throw new DataMapperException('Your argument needs to be an array');
        }
    }
    /**
     * Summary of prepare
     * @param string $sqlQuery
     * @return DataMapper
     */
    public function prepare(string $sqlQuery): self
    {
        $this->statement = $this->connection->pdo()->prepare($sqlQuery);
        return $this;
    }
    /**
     * Summary of bind
     * @param mixed $value
     * @return mixed
     */
    public function bind($value): mixed
    {
        try {
            switch ($value) {
                case is_bool($value):
                case intval($value);
                    $dataType = PDO::PARAM_INT;
                    break;
                case is_null($value):
                    $dataType = PDO::PARAM_NULL;
                    break;
                default:
                    $dataType = PDO::PARAM_STR;
                    break;
            }
            return $dataType;
        } catch (DataMapperException $exception) {
            throw $exception;
        }
    }
    /**
     * Summary of bindParameters
     * @param array $fields
     * @param bool $isSearch
     * @return DataMapper
     */
    public function bindParameters(array $fields, bool $isSearch = false): self
    {
        if (is_array($fields)) {
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
        return false;
    }
    /**
     * Summary of bindValues
     * @param array $fields
     * @return PDOStatement
     */
    protected function bindValues(array $fields): PDOStatement
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, $value, $this->bind($value));
        }
        return $this->statement;
    }
    /**
     * Summary of bindSearchValues
     * @param array $fields
     * @return PDOStatement
     */
    protected function bindSearchValues(array $fields): PDOStatement
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key,  '%' . $value . '%', $this->bind($value));
        }
        return $this->statement;
    }
    /**
     * Summary of execute
     * @return bool
     */
    public function execute()
    {
        if ($this->statement)
            return $this->statement->execute();
    }
    /**
     * Summary of numRows
     * @return int
     */
    public function numRows(): int
    {
        if ($this->statement)
            return $this->statement->rowCount();
    }
    /**
     * Summary of result
     * @return object
     */
    public function result(): Object
    {
        if ($this->statement)
            return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @inheritDoc
     */
    public function results(): array
    {
        if ($this->statement)
            return $this->statement->fetchAll();
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function getLastId(): int
    {
        try {
            if ($this->connection->pdo()) {
                $lastID = $this->connection->pdo()->lastInsertId();
                if (!empty($lastID)) {
                    return intval($lastID);
                }
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Summary of buildQueryParameters
     * @param array $conditions
     * @param array $parameters
     * @return array
     */
    public function buildQueryParameters(array $conditions = [], array $parameters = []): array
    {
        return (!empty($parameters) || (!empty($conditions)) ? array_merge($conditions, $parameters) : $parameters);
    }

    /**
     * Summary of persist
     * @param string $sqlQuery
     * @param array $parameters
     * @return mixed
     */
    public function persist(string $sqlQuery, array $parameters)
    {
        try {
            return $this->prepare($sqlQuery)->bindParameters($this->buildQueryParameters($parameters))->execute();
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }
}
