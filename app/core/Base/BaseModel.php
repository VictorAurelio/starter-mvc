<?php

namespace App\Core\Base;

use App\Core\Base\Exceptions\BaseInvalidArgumentException;
use App\Core\Database\DataRepository\DataRepository;
use App\Core\Database\DataRepository\DataRepositoryFactory;

class BaseModel
{
    private string $tableSchema;
    private string $tableSchemaId;
    private DataRepository $repository;
    public function __construct(string $tableSchema, string $tableSchemaId)
    {
        if (empty($tableSchema || empty($tableSchemaId))) {
            throw new BaseInvalidArgumentException('These arguments are required.');
        }
        $factory = new DataRepositoryFactory('', $tableSchema, $tableSchemaId);
        $this->repository = $factory->create(DataRepository::class);
    }

    public function getRepository(): DataRepository
    {
        return $this->repository;
    }
}
