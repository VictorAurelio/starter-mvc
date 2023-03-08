<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller{
    public function index() {
        echo 'user';
    }
    public function findUser() {

    }
    public function signUp() {
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
    
        if (empty($name) || 
            empty($email) || 
            empty($password) || 
            empty($password_confirmation)) {

            $this->json(['message' => 'Name and email are required'], 400);
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
        $user = $newUser->first(['email' => $email]);
        
        if ($user) {
            $this->json(['message' => 'Email address already in use'], 400);
        }
    
        $newUser->create($data);        
    
        $this->json(['message' => 'User created successfully'], 201);
    }
    public function signIn() {

    }

}