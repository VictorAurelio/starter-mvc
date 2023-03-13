<?php

namespace App\Core\Database\DataRepository;

use App\Core\Database\DataRepository\Exception\DataRepositoryException;

class DataRepositoryFactory
{
    protected string $tableSchema;
    protected string $tableSchemaId;
    protected string $crudIdentifier;

    public function __construct(string $crudIdentifier, string $tableSchema, string $tableSchemaId)
    {
        $this->crudIdentifier = $crudIdentifier;
        $this->tableSchemaId = $tableSchemaId;
        $this->tableSchema = $tableSchema;
    }

    public function create(string $dataRepositoryString)
    {
        $dataRepositoryObject = new $dataRepositoryString();
        if(!$dataRepositoryObject instanceof DataRepositoryInterface)
        {
            throw new DataRepositoryException($dataRepositoryString . 'is not a valid repository object!');
        }
        return $dataRepositoryObject;
    }
}