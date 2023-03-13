<?php

namespace App\Models;
use App\Core\Base\Abstracts\AbstractBaseModel;

class UserModel extends AbstractBaseModel
{
    protected const TABLESCHEMA = 'users';
    protected const TABLESCHEMAID = 'id';
    public function __construct()
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID);
    }
    public function lockedId(): array
    {
        return [];
    }
}