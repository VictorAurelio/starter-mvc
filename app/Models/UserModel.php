<?php

namespace App\Models;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Base\BaseModel;
use App\Models\Jwt;

/**
 * Summary of UserModel
 */
class UserModel extends BaseModel
{
    protected const TABLESCHEMA = 'users';
    protected const TABLESCHEMAID = 'id';
    private $userId;
    protected ConnectionInterface $connection;
    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, $connection);
    }

    public function checkCredentials($email, $password)
    {   
        $user = $this->dao->search(['email'], ['email' => $email], true);
        if (count($user) > 0 && $user[0]['email'] === $email) {
            if (password_verify($password, $user[0]['password'])) {
                $this->userId = $user[0]['id'];
                return $user[0];
            }
        }
        return json_encode(['error' => 'Invalid email or password']);
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
