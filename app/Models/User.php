<?php

namespace App\Models;

use App\Core\Model;
use App\Models\Jwt;

class User
{
    protected $table = 'users';
    private $userId;
    protected $allowedColumns = [
        'name',
        'email',
        'password'
    ];
    // With this only I can already use it in my UserController to interact with Database
    // I can either put some validations here or in my controller - or even both
    public function checkCredentials($email, $password)
    {
        $userInfo = $this->select(['*'])->where(['email' => $email]);

        if ($userInfo && count($userInfo) > 0) {
            $userInfo = $userInfo[0];
            if (password_verify($password, $userInfo->password)) {
                $this->userId = $userInfo->id;
                // var_dump($this->userId);               
                return $this->userId;
            } else {
                //json_encode(['error' => 'Invalid Password!'], 400);
                return false;
            }
        } else {
            //json_encode(['error' => 'User does not exist!'], 400);
            return false;
        }
    }
    public function getId()
    {
        return $this->userId;
    }
    public function validateJwt($token)
    {
        $jwt = new Jwt();
        $info = $jwt->validate($token);
        if (isset($info->userId)) {
            $this->userId = $info->userId;
            return true;
        } else {
            return false;
        }
    }
    public function createJwt($userId = [])
    {
        $jwt = new Jwt();
        $userId = $this->userId;
        return $jwt->create(['userId' => $userId]);
    }
}
