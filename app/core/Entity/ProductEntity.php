<?php

namespace App\Core\Entity;
use App\Core\Base\BaseEntity;

class ProductEntity extends BaseEntity
{
    public function __construct(array $dirtyData)
    {
        parent::__construct($dirtyData);
    }
}