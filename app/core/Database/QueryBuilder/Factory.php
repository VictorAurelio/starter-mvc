<?php

namespace App\Core\Database\QueryBuilder;

use App\Core\Database\QueryBuilder\QueryBuilderInterface;
use App\Core\Database\QueryBuilder\Exception\QueryBuilderException;
/**
 * Summary of Factory
 */
class Factory {
    /**
     * Summary of __construct
     * @return void
     */
    public function __construct() {}

    public function create(string $queryBuilderString): QueryBuilderInterface
    {
        $queryBuilderObject = new $queryBuilderString();
        if(!$queryBuilderString instanceof QueryBuilderInterface) {
            // throw new QueryBuilderException($queryBuilderString . 'is not a valid Query builder object.');
        }
        return $queryBuilderObject;
    }
}