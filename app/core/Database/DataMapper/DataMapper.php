<?php
namespace App\Core\Database\DataMapper;

use App\Core\Database\Connection\Connection;
use App\Core\Database\DataMapper\Exception\DataMapperException;
use PDO;
use PDOStatement;
use Throwable;

class DataMapper implements DataMapperInterface {    
    protected Connection $connection;
    private PDOStatement $statement;
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            throw new DataMapperException($errorMessage);
        }
    }
    private function isArray(array $value)
    {
        if (!is_array($value)) {
            throw new DataMapperException('Your argument needs to be an array');
        }
    }
    public function prepare(string $sqlQuery) : self
    {
        $this->statement = $this->connection->pdo()->prepare($sqlQuery);
        return $this;
    }
    public function bind($value)
    {
        try {
            switch($value) {
                case is_bool($value) :
                case intval($value);
                    $dataType = PDO::PARAM_INT;
                    break;
                case is_null($value) :
                    $dataType = PDO::PARAM_NULL;
                    break;
                default :
                    $dataType = PDO::PARAM_STR;
                    break;
            }
            return $dataType;
        } catch(DataMapperException $exception) {
            throw $exception;
        }
    }
    public function bindParameters(array $fields, bool $isSearch = false) : self
    {
        if (is_array($fields)) {
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
        return false;
    }
    protected function bindValues(array $fields) : PDOStatement
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, $value, $this->bind($value));
        }
        return $this->statement;
    }
    protected function bindSearchValues(array $fields) :  PDOStatement
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key,  '%' . $value . '%', $this->bind($value));
        }
        return $this->statement;
    }
    public function execute(): bool
    {
        if ($this->statement)
            return $this->statement->execute();
    }
    public function numRows(): int
    {
        if ($this->statement)
            return $this->statement->rowCount();
    }
    public function result() : Object
    {
        if ($this->statement)
            return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @inheritDoc
     */
    public function results() : array
    {
        if ($this->statement)
            return $this->statement->fetchAll();
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function getLastId() : int
    {
        try {
            if ($this->connection->pdo()) {
                $lastID = $this->connection->pdo()->lastInsertId();
                if (!empty($lastID)) {
                    return intval($lastID);
                }
            }
        }catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    public function buildQueryParameters(array $conditions = [], array $parameters = []) : array
    {
        return (!empty($parameters) || (!empty($conditions)) ? array_merge($conditions, $parameters) : $parameters);
    }

    public function persist(string $sqlQuery, array $parameters)
    {
        try {
            return $this->prepare($sqlQuery)->bindParameters($parameters)->execute();
        } catch(Throwable $throwable){
            throw $throwable;
        }
    }

    //     $db = $this->connect();
	// 	$stmt = $this->db->prepare($query);

	// 	$check = $stmt->execute($data);
	// 	if($check)
	// 	{
	// 		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	// 		if(is_array($result) && count($result))
	// 		{
	// 			return $result[0];
	// 		}
	// 	}
	// 	return false;
	// }
}