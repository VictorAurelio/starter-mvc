<?php

namespace App\Http\Controllers\User;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Validation\Rule\Data\DataSanitizer;
use App\Core\Validation\Validator;
use App\Core\Database\DAO\DAO;
use App\Models\UserModel;

class RegisterUserController extends UserController
{
    protected Validator $validator;
    protected DataSanitizer $sanitizer;
    protected UserModel $userModel;
    protected ConnectionInterface $connection;
    protected DAO $dao;
    public function __construct(UserController $userController)
    {
        $this->validator = $userController->validator;
        $this->sanitizer = $userController->sanitizer;
        $this->userModel = $userController->userModel;
        $this->connection = $userController->connection;
        $this->dao = $userController->dao;
    }

    public function register(array $data): array
    {
        $data = $this->sanitizer->clean($data);

        $this->validator->validate($data, [
            'name' => ['required'],
            'email' => ['required', 'unique'],
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'match:password']
        ]);

        unset($data['password_confirmation']);

        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['password'] = $password_hash;

        if (!$password_hash) {
            throw new \Exception('Error hashing password');
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

        return ['message' => 'User created successfully', 'jwt' => $jwt, 'status' => 201];
    }
}
