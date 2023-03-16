<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\User\UserController;

class LogoutUserController extends UserController
{
    public function __construct(UserController $userController)
    {
        parent::__construct();
    }
    public function logout($token)
    {
        $userIdFromJwt = $this->userModel->getUserIdFromJwt($token);
    
        return $userIdFromJwt !== false;
    }
}