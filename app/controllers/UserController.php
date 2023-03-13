<?php

namespace App\Controllers;

use App\Core\Base\BaseController;
use App\Models\User;

class UserController extends BaseController
{
    public function index()
    {
        echo 'user';
    }
    public function view($id)
    {
        $array = ['error' => '', 'logged' => false];

        $method = $this->getMethod();
        $payload = $this->getRequestData();
        $user = new User();

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
        $newUser = new User();
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

        if (
            empty($name) ||
            empty($email) ||
            empty($password) ||
            empty($password_confirmation)
        ) {

            $this->json(['message' => 'All fields are required'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['message' => 'Invalid email address'], 400);
        }

        if ($password !== $password_confirmation) {
            $this->json(['message' => 'Passwords do not match'], 400);
        }

        if (strlen($password) < 8) {
            $this->json(['message' => 'Password must be at least 8 characters'], 400);
        }

        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // If the data is valid, create a new user
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password_hash
        ];

        // Check if the email already exists
        // $user = $newUser->first(['email' => $email]);

        // if ($user) {
        //     $this->json(['message' => 'Email address already in use'], 400);
        // }

        // $newUser->create($data);

        // Create a JWT for the newly created user
        $userId = $newUser->checkCredentials($email, $password);
        $jwt = $newUser->createJwt($userId);

        $this->json(['message' => 'User created successfully', 'jwt' => $jwt], 201);
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
