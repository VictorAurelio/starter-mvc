<?php

namespace App\Core\Database\DAO;

use App\Core\Database\DAO\DAOInterface;

class DAOManager implements DAOManagerInterface
{
    /**
     * @var DAOInterface
     */
    protected DAOInterface $crud;

    /**
     * Main constructor class
     * 
     * @return void
     */
    public function __construct(DAOInterface $crud)
    {
        $this->crud = $crud;
    }

    /**
     * @inheritDoc
     */
    public function getCrud() : Object
    {
        return $this->crud;
    }

}