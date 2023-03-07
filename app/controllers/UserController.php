<?php

namespace App\Controllers;

use App\Models\User;

class UserController {

    public function index() {
        echo 'user';
    }
    public function findUser() {

    }
    public function signUp() {
        $newUser = new User();
        $data = [
            'name' => 'Marcos',
            'email' => 'sadfaokf@gmail.com'
        ];
        $newUser->create($data);
    }
    public function signIn() {

    }
    public function profile($id) {
        echo $id.'<br>';
    }

}