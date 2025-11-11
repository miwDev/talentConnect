<?php

namespace App\core\data;

use App\core\data\DBC;
use App\core\helper\Security;
use PDO;
use PDOException;
use Exception;


class Authorization
{

    public static function verifyUser($username, $password)
    {
        $conn = DBC::getConnection();
        $verified = false;

        $query = 'SELECT id, user_name, passwrd, role_id
              FROM USER
              WHERE user_name = :username';

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado && password_verify($password, $resultado['passwrd'])) {
            $userId = $resultado['id'];
            $roleId = $resultado['role_id'];

            $token = self::updateToken($userId);

            if ($roleId === 2) {

                $userObject = AlumnoRepo::findByUserId($userId);
            } elseif ($roleId === 3) {

                $userObject = EmpresaRepo::findByUserId($userId);
            } else {
                $adminObject = new \stdClass();
                $adminObject->id = $resultado['id'];
                $adminObject->username = $resultado['user_name'];

                $userObject = $adminObject;
            }
            if ($userObject) {
                $verified = [
                    "user" => $userObject,
                    "token" => $token
                ];
            }
        }

        return $verified;
    }

    public static function updateToken($user_id)
    {
        $conn = DBC::getConnection();
        $token = Security::generateToken();

        $query = 'UPDATE USER_TOKEN 
                      SET token = :token 
                      WHERE user_id = :userId';

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':userId', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $token;
    }
}
