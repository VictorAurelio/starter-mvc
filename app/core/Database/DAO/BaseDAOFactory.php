<?php

namespace App\Core\Database\DAO;

use App\Core\Database\QueryBuilder\QueryBuilderInterface;
use App\Core\Database\DataMapper\DataMapperInterface;
use App\Core\Database\DAO\Exception\DAOException;
use App\Core\Database\DAO\HelperDAOInterface;
use App\Core\Database\DAO\HelperDAO;


/**
 * Summary of DAOFactory
 */
class BaseDAOFactory
{
    protected QueryBuilderInterface $queryBuilder;
    protected DataMapperInterface $dataMapper;

    /**
     * Summary of __construct
     * @param QueryBuilderInterface $queryBuilder
     * @param DataMapperInterface $dataMapper
     */
    public function __construct(DataMapperInterface $dataMapper, QueryBuilderInterface $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
        $this->dataMapper = $dataMapper;
    }

    public function create(string $crudString, string $tableSchema, string $tableSchemaId, array $options = []): HelperDAOInterface
    {
        $crudObject = new $crudString($this->dataMapper, $this->queryBuilder, $tableSchema, $tableSchemaId);
        if(!$crudObject instanceof BaseDAOInterface) {
            throw new DAOException($crudString . ' is not a valid crud object.');
        }
        return new HelperDAO($crudObject);
    }
}
