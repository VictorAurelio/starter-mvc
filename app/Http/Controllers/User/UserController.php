<?php

namespace App\Http\Controllers\User;

use App\Core\Validation\Exception\ValidationException;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Validation\Rule\Data\DataSanitizer;
use App\Core\Database\DataMapper\DataMapper;
use App\Core\Validation\Rule\RequiredRule;
use App\Core\Validation\Rule\UniqueRule;
use App\Core\Validation\Rule\MatchRule;
use App\Core\Database\DatabaseHandler;
use App\Core\Validation\Rule\MinRule;
use App\Core\Validation\Validator;
use App\Core\Base\BaseController;
use App\Core\Database\DAO\DAO;
use App\Models\UserModel;


class UserController extends BaseController
{
    protected Validator $validator;
    protected DataSanitizer $sanitizer;
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
        $this->validator = new Validator();
        $this->validator
            ->addRule('required', new RequiredRule())
            ->addRule('match', new MatchRule())
            ->addRule('unique', new UniqueRule($this->connection, 'users', 'email'))
            ->addRule('min', new MinRule());

        $this->sanitizer = new DataSanitizer();
    }
    public function index()
    {
        // $users = $this->dao->read(['name', 'email']);
        // var_dump($users);
    }
    // public function view($id)
    // {
    //     $array = ['error' => '', 'logged' => false];

    //     $method = $this->getMethod();
    //     $payload = $this->getRequestData();

    //     if (!empty($payload['jwt']) && $user->validateJwt($payload['jwt'])) {
    //         $array['logged'] = true;
    //         $array['self'] = false;
    //         if ($id == $user->getId()) {
    //             $array['self'] = true;
    //         }
    //     } else {
    //         $array['error'] = 'acesso negado';
    //     }

    //     // $this->json($array);
    // }
    public function search()
    {
    }
    public function signUp()
    {
        if ($this->getMethod() !== 'POST') {
            $this->json(['message' => 'Invalid method for signing up'], 405);
        }

        // Read the request data
        $payload = $this->getRequestData();
        $data = $this->sanitizer->clean($payload);

        $registerUserController = new RegisterUserController($this);

        try {
            $result = $registerUserController->register($data);
            $this->json($result['message'], $result['status']);
        } catch (ValidationException $e) {
            $this->json($e->getErrors(), 400);
        }
    }

    // public function signIn()
    // {
    //     $payload = $this->getRequestData();
    //     var_dump($payload);

    //     $array = match (true) {
    //         ($this->getMethod() !== 'POST') => ['error' => 'Método incompatível'],
    //         (empty($payload['email']) || empty($payload['password'])) => ['error' => 'Preencha todos os campos.'],
    //         ($this->userModel->checkCredentials($payload['email'], $payload['password'])) => ['jwt' => $this->userModel->createJwt()],
    //         default => ['error' => 'Informações inválidas.']
    //     };

    //     // $this->json($array);
    // }
}
