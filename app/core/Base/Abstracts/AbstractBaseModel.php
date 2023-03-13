<?php

namespace App\Core\Base\Abstracts;

use App\Core\Base\BaseModel;

abstract class AbstractBaseModel extends BaseModel
{
    abstract public function lockedId(): array;
}