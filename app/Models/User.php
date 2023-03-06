<?php
namespace App\Models;
use App\Core\Model;
class User {
    use Model;
    protected $table = 'users';
    protected $allowedColumns = [
        'name',
        'email',
        'password'
    ];
    // With this only I can already use it in my UserController to interact with Database
    // I can either put some validations here or in my controller - or even both
}