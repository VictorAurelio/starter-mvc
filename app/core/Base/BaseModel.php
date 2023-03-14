<?php

namespace App\Core\Base;

use App\Core\Base\Exceptions\BaseInvalidArgumentException;

class BaseModel
{
    private string $tableSchema;
    private string $tableSchemaId;
    public function __construct(string $tableSchema, string $tableSchemaId)
    {
        if (empty($tableSchema || empty($tableSchemaId))) {
            throw new BaseInvalidArgumentException('These arguments are required.');
        }
    }
}
