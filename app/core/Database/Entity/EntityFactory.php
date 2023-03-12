<?php

namespace App\Core\Database\DAO;

use App\Core\Database\Entity\Exception\CrudException;
use App\Core\Database\DataMapper\DataMapperInterface;
use App\Core\Database\QueryBuilder\QueryBuilderInterface;

class EntityFactory
{
    protected DataMapperInterface $dataMapper;
    protected QueryBuilderInterface $queryBuilder;

    public function __construct(DataMapperInterface $dataMapper, QueryBuilderInterface $queryBuilder)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
    }

    public function create(string $crudString, string $tableSchema, string $tableSchemaId, array $options = [])
    {
        $crudObject = new $crudString($this->dataMapper, $this->queryBuilder, $tableSchema, $tableSchemaId);
        if(!$crudObject instanceof CrudInterface) {
            throw new CrudException($crudString . ' is not a valid crud object.');
        }
        return new $crudObject;
    }
}
