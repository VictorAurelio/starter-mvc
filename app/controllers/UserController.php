<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Core;

class UserController {
    protected $core;
    public function __construct() {
        $this->core = new Core();
    }
    public function index() {
        echo 'user';
    }
    public function findUser() {

    }
    public function signUp() {
        $this->core->runMiddleware();
        $newUser = new User();
        $method = $_SERVER['REQUEST_METHOD'];
        
        if($method !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['message' => 'Invalid method for signing up']);
            return;
        }

        // Read and parse the JSON payload from the request body
        $payload = json_decode(file_get_contents('php://input'), true);
        
        // Validate the request data
        $name = $payload['name'] ?? null;
        $email = $payload['email'] ?? null;
        $password = $payload['password'] ?? null;
        $password_confirmation = $payload['password_confirmation'] ?? null;
        
        if( empty($name) ||
            empty($email) ||
            empty($password) ||
            empty($password_confirmation))
        {
            http_response_code(400);
            echo json_encode(['message' => 'Name and email are required']);
            return;
        }
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid email address']);
            return;
        }

        if($password !== $password_confirmation) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Passwords do not match']);
            return;
        }

        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // If the data is valid, create a new user
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password_hash
        ];
        
        $newUser->create($data);
        
        http_response_code(201); // Created
        echo json_encode(['message' => 'User created successfully']);       
    }
    public function signIn() {

    }
    public function profile($id) {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method === 'GET') 
            echo 'ID:'.$id.'<br>'.'IF: Method: '.$method;
        else 
            echo 'Method: '.$method;
    }

}