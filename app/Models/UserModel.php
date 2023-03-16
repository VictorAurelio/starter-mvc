<?php

namespace App\Models;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Model;
use App\Models\Jwt;

/**
 * Summary of UserModel
 */
class UserModel extends Model
{
    protected const TABLESCHEMA = 'users';
    protected const TABLESCHEMAID = 'id';
    protected ConnectionInterface $connection;
    protected JWT $jwt;
    private $userId;
    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, $connection);
        $this->jwt = new JWT();
    }

    public function checkCredentials($email, $password)
    {
        $user = $this->dao->findByExact(['email' => $email]);
        
        if ($user !== null && password_verify($password, $user->password)) {
            $this->userId = $user->id;
            return $user;
        }
        return false;
    }
    public function getUserIdFromJwt($token)
    {
        $info = $this->jwt->validate($token);
        if (isset($info->userId)) {
            return $info->userId;
        } else {
            return false;
        }
    }
    public function getTableSchema()
    {
        return self::TABLESCHEMA;
    }
    public function getTableSchemaId()
    {
        return self::TABLESCHEMAID;
    }
    public function validateJwt($token)
    {
        $info = $this->jwt->validate($token);
        // var_dump($info);
        if (isset($info->userId) && isset($info->exp) && time() < $info->exp) {
            $this->userId = $info->userId;
            return true;
        } else {
            return false;
        }
    }
    public function createJwt($userId)
    {
        $expTime = time() + JWT_EXPIRATION_TIME;
        $token = $this->jwt->create(['userId' => $userId, 'exp' => $expTime]);
        return $token;
    }
}
