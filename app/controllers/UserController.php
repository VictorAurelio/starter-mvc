<?php

namespace App\Controllers;

use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DataMapper\DataMapper;
use App\Core\Database\DatabaseHandler;
use App\Core\Base\BaseController;
use App\Core\Database\DAO\DAO;
use App\Models\UserModel;

class UserController extends BaseController
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
    public function signUp()
    {
        $method = $this->getMethod();
        if ($method !== 'POST') {
            $this->json(['message' => 'Invalid method for signing up'], 405);
        }

        // Read the request data
        $payload = $this->getRequestData();

        // Validate the request data
        $name = $payload['name'] ?? null;
        $email = $payload['email'] ?? null;
        $password = $payload['password'] ?? null;
        $password_confirmation = $payload['password_confirmation'] ?? null;

        $errors = [];

        empty($name) && $errors[] = 'Name is required';
        empty($email) && $errors[] = 'Email is required';
        empty($password) && $errors[] = 'Password is required';
        empty($password_confirmation) && $errors[] = 'Password confirmation is required';
        
        !filter_var($email, FILTER_VALIDATE_EMAIL) && $errors[] = 'Invalid email address';
        
        $password !== $password_confirmation && $errors[] = 'Passwords do not match';
        strlen($password) < 8 && $errors[] = 'Password must be at least 8 characters';
        
        if (!empty($errors)) {
            $this->json(['message' => $errors], 400);
        }
        
       // Verify if email already exists in database
       $userWithEmail = $this->dao->search(['email'], ['email' => $email]);

       $error = match (true) {
           !empty($userWithEmail) => 'Email address already in use',
           default => null
       };
       
       if ($error) {
           $this->json(['message' => $error], 400);
       }
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // If the data is valid, create a new user
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => ($password_hash) ? $password_hash : null
        ];                
        if (!$data['password']) {
            $this->json(['message' => 'Error hashing password'], 500);
        } else {
            $newUser = $this->dao->create($data);
            if ($newUser) {
                $user = $this->userModel->checkCredentials($email, $password);
                if ($user) {
                    $userId = $user['id'];
                    $jwt = $this->userModel->createJwt($userId);
                    $this->json(['message' => 'User created successfully', 'jwt' => $jwt], 201);
                }
            } else {
                $this->json(['message' => 'Error creating user'], 500);
            }
        }
    }
    public function signIn()
    {
        $array = ['error' => ''];

        $payload = $this->getRequestData();
        $method = $this->getMethod();

        if ($method === 'POST') {
            if (!empty($payload['email']) && !empty($payload['password'])) {
                $user = new User();
                if ($user->checkCredentials($payload['email'], $payload['password'])) {
                    // Generate JWT
                    $array['jwt'] = $user->createJwt();
                } else {
                    $array['error'] = 'Informações inválidas.';
                }
            } else {
                $array['error'] = 'Preencha todos os campos.';
            }
        } else {
            $array['error'] = 'Método incompatível';
        }

        $this->json($array);
    }
}
