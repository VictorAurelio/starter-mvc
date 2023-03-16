<?php

namespace App\Controllers;

use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DataMapper\DataMapper;
use App\Core\Database\DatabaseHandler;
use App\Core\Base\BaseController;
use App\Core\Database\DAO\DAO;
use App\Core\Validation\Exception\ValidationException;
use App\Core\Validation\Rule\Data\DataSanitizer;
use App\Core\Validation\Rule\MatchRule;
use App\Models\UserModel;

use App\Core\Validation\Validator;
use App\Core\Validation\Rule\RequiredRule;
use App\Core\Validation\Rule\MinRule;

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
            ->addRule('min', new MinRule());
            
        $this->sanitizer = new DataSanitizer();
    }
    public function index()
    {
        $users = $this->dao->read(['name', 'email']);
        var_dump($users);
    }
    public function view($id)
    {
        $array = ['error' => '', 'logged' => false];

        $method = $this->getMethod();
        $payload = $this->getRequestData();

        if (!empty($payload['jwt']) && $user->validateJwt($payload['jwt'])) {
            $array['logged'] = true;
            $array['self'] = false;
            if ($id == $user->getId()) {
                $array['self'] = true;
            }
        } else {
            $array['error'] = 'acesso negado';
        }

        // $this->json($array);
    }
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

        // Validate the request data
        try {
            $this->validator->validate($data, [
                'name' => ['required'],
                'email' => ['required'],
                'password' => ['required', 'min:8'],
                'password_confirmation' => ['required', 'match:password']
            ]);
        } catch (ValidationException $e) {
            $this->json($e->getErrors(), 400);
        }
        unset($data['password_confirmation']);
        $result = $this->createUserWithData($data);

        $this->json($result['message'], $result['status']);
    }

    protected function createUserWithData(array $data)
    {
        $data = $this->sanitizer->clean($data);

        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['password'] = $password_hash;

        if (!$password_hash) {
            $this->json(['message' => 'Error hashing password'], 500);
        }
        // Verify if email already exists in database
        $userWithEmail = $this->dao->search(['email'], ['email' => $data['email']], true);
        $error = match (true) {
            !empty($userWithEmail) => 'Email address already in use',
            default => null
        };
        
        if ($error) {
            $this->json(['message' => $error], 400);
        }

        $user = $this->dao->create($data);

        if (!$user) {
            return ['message' => 'Error creating user', 'status' => 500];
        }

        $userId = $this->userModel->checkCredentials($data['email'], $data['password']);

        if (!$userId) {
            return ['message' => 'Invalid email or password', 'status' => 401];
        }

        $jwt = $this->userModel->createJwt($userId);

        $this->json(['message' => 'User created successfully', 'jwt' => $jwt], 201);
    }
    public function signIn()
    {
        $payload = $this->getRequestData();
        var_dump($payload);
    
        $array = match (true) {
            ($this->getMethod() !== 'POST') => ['error' => 'Método incompatível'],
            (empty($payload['email']) || empty($payload['password'])) => ['error' => 'Preencha todos os campos.'],
            ($this->userModel->checkCredentials($payload['email'], $payload['password'])) => ['jwt' => $this->userModel->createJwt()],
            default => ['error' => 'Informações inválidas.']
        };
    
        // $this->json($array);
    }
}
