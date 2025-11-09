<?php

namespace App\core\data;

use App\core\data\DBC;
use PDO;
use PDOException;
use Exception;


class Authorization
{

    public static function verifyUser($username, $password)
    {
        $conn = DBC::getConnection();
        $verification = "COMETE UN MOJON";

        $query = 'SELECT id, user_name, passwrd, role_id
              FROM USER
              WHERE user_name = :username';

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado && password_verify($password, $resultado['passwrd'])) { //compares the plain text pass with the hashed one in the bd
            $verification = "VERIFICACION CORRECTA";
        }

        return $verification;
    }

    public static function verifyToken($id, $token)
    {
        return true;
    }

    public static function updateToken($id, $token)
    {
        return true;
    }
}
