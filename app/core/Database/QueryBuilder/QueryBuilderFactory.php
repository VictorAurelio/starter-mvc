<?php

namespace App\Core\Database\QueryBuilder;

use App\Core\Database\QueryBuilder\Exception\QueryBuilderException;
use App\Core\Database\QueryBuilder\QueryBuilderInterface;

/**
 * Summary of Factory
 */
class QueryBuilderFactory
{
    /**
     * Summary of __construct
     * @return void
     */
    public function __construct()
    {
    }

    public function create(string $queryBuilderString): QueryBuilderInterface
    {
        $queryBuilderObject = new $queryBuilderString();
        if (!$queryBuilderString instanceof QueryBuilderInterface) {
            // throw new QueryBuilderException($queryBuilderString . 'is not a valid Query builder object.');
        }
        return $queryBuilderObject;
    }
}
