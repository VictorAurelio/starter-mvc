<?php

namespace App\Http\Controllers;

use App\Core\Database\DatabaseHandler;
use App\Core\Base\BaseController;
use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DAO\DAO;
use App\Core\Database\DataMapper\DataMapper;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Models\UserModel;

class HomeController extends BaseController
{
    protected UserModel $userModel;
    protected ConnectionInterface $connection;
    protected DAO $dao;
    public function __construct()
    {
        $this->connection = DatabaseHandler::createConnection();
        $this->userModel = new UserModel($this->connection);
        $this->dao = new DAO(
            new DataMapper($this->connection),
            new MysqlQueryBuilder($this->connection),
            $this->userModel->getTableSchema(),
            $this->userModel->getTableSchemaId()
        );
    }

    public function index()
    {
        echo 'home';
    }
}
