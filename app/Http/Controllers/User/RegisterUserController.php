<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\User\UserController;

class RegisterUserController extends UserController
{
    public function __construct(UserController $userController)
    {
        parent::__construct();
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
