<?php


namespace App\Core\Database\DAO;

use App\Core\Database\QueryBuilder\QueryBuilder;
use App\Core\Database\DataMapper\DataMapper;
use Exception;
use Throwable;

class DAO
{
    /** @var QueryBuilder */
    protected QueryBuilder $queryBuilder;

    /** @var DataMapper */
    protected DataMapper $dataMapper;

    /** @var string */
    protected string $tableSchemaID;

    /** @var string */
    protected string $tableSchema;

    /** @var array */
    protected array $options;

    /**
     * Main constructor
     *
     * @param QueryBuilder $queryBuilder
     * @param DataMapper $dataMapper
     * @param string $tableSchemaID
     * @param string $tableSchema
     */
    public function __construct(DataMapper $dataMapper, QueryBuilder $queryBuilder, string $tableSchema, string $tableSchemaID, ?array $options = [])
    {
        $this->tableSchemaID = $tableSchemaID;
        $this->queryBuilder = $queryBuilder;
        $this->tableSchema = $tableSchema;
        $this->dataMapper = $dataMapper;
        $this->options = $options;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getSchema(): string
    {
        return (string)$this->tableSchema;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getSchemaID(): string
    {
        return (string)$this->tableSchemaID;
    }

    /**
     * @inheritdoc
     *
     * @return integer
     */
    public function lastID(): int
    {
        return $this->dataMapper->getLastId();
    }

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @return boolean
     */
    public function create(array $fields = []): bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'insert', 'fields' => $fields];
            $query = $this->queryBuilder->buildQuery($args)->insertQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
            if ($this->dataMapper->numRows() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }


    /**
     * @inheritdoc
     *
     * @param array $conditions
     * @param array $parameters
     * @param array $selectors
     * @param array $optional
     * @return array
     */
    public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions, 'params' => $parameters, 'extras' => $optional];
            $query = $this->queryBuilder->buildQuery($args)->selectQuery();
            var_dump($query);
            var_dump($this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters)));
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
            if ($this->dataMapper->numRows() > 0) {
                return $this->dataMapper->results();
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @inheritdoc
     *
     * @param string $primaryKey
     * @param array $fields
     * @return boolean
     */
    public function update(array $fields = [], string $primaryKey): bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'update', 'fields' => $fields, 'primary_key' => $primaryKey];
            $query = $this->queryBuilder->buildQuery($args)->updateQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
            if ($this->dataMapper->numRows() === 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritdoc
     *
     * @param array $conditions
     * @return boolean
     */
    public function delete(array $conditions = []): bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'delete', 'conditions' => $conditions];
            $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->numRows() === 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

   /**
     * @inheritdoc
     *
     * @param array $selectors
     * @param array $conditions
     * @return array
     * @throws DataLayerException
     */
    public function search(array $selectors = [], array $conditions = [], bool $exact = false): array
    {
        $args = ['table' => $this->getSchema(), 'type' => 'search', 'selectors' => $selectors, 'conditions' => $conditions, 'isSearch' => !$exact];
        $query = $exact ? $this->queryBuilder->buildQuery($args)->searchQueryExact() : $this->queryBuilder->buildQuery($args)->searchQuery();
        var_dump($query);
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions), true);
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->results() : array();
    }
    /**
     * @inheritdoc
     *
     * @param array|null $conditions
     * @param string $rawQuery
     * @return void
     */
    public function rawQuery(string $rawQuery, ?array $conditions = [])
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'raw', 'raw' => $rawQuery, 'conditions' => $conditions];
            $query = $this->queryBuilder->buildQuery($args)->rawQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->numRows()) {
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }   
    /**
     * @param string $type
     * @return mixed
     */
    public function getQueryType(string $type)
    {
        $queryTypes = ['createQuery', 'readQuery', 'updateQuery', 'deleteQuery', 'joinQuery', 'searchQuery', 'rawQuery'];
        if (!empty($type)) {
            if (in_array($type, $queryTypes, true)) {
                return $this->$type;
            }
        }

    }
}
