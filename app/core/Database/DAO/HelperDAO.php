<?php

namespace App\Core\Database\DAO;

use App\Core\Database\DAO\HelperDAOInterface;
use App\Core\Database\DAO\BaseDAOInterface;

class HelperDAO implements HelperDAOInterface
{
    protected BaseDAOInterface $crud;
    public function __construct(BaseDAOInterface $crud)
    {
        $this->crud = $crud;
    }
    public function getCrud(): object
    {
        return $this->crud;
    }
}