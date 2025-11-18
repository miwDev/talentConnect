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

                if($userObject->validacion === 0){
                    $userObject = false;
                }
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
            }else{
                $verified = ["user" => "empresaNoVerificada"];
            }
        }

        return $verified;
    }


    public static function registeredAlumno($alumnoId)
    {
        $conn = DBC::getConnection();
        $verified = false;

        // Obtener user_id y role_id desde el alumno_id
        $query = 'SELECT u.id, u.user_name, u.role_id
                FROM USER u
                JOIN ALUMNO a ON u.id = a.user_id
                WHERE a.id = :alumno_id';
        
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':alumno_id', $alumnoId, PDO::PARAM_INT);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            $userId = $resultado['id'];
            $roleId = $resultado['role_id'];
            
            // Generar y actualizar token
            $token = self::updateToken($userId);
            
            // Obtener el objeto completo del usuario según su rol
            if ($roleId === 2) {
                $userObject = AlumnoRepo::findByUserId($userId);
            } elseif ($roleId === 3) {
                $userObject = EmpresaRepo::findByUserId($userId);
            } else {
                // Admin u otro rol
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

    public static function getRoleByToken($token)
    {
        $db = DBC::getConnection(); 
        $role = null;
        
        try {
            $sql = "SELECT r.role_name as role_name
                    FROM USER_TOKEN ut
                    INNER JOIN USER u ON ut.user_id = u.id
                    INNER JOIN ROLE r ON u.role_id = r.id
                    WHERE ut.token = :token
                    LIMIT 1";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':token', $token);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $role = $result['role_name'];
            }

        } catch (\Exception $e) {
            error_log("Error en getRoleByToken: " . $e->getMessage());
            $role = null;
        }

        return $role;
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

    public static function deleteToken($token)
    {
        $conn = DBC::getConnection();
        $success = false;
        
        $query = 'UPDATE USER_TOKEN 
                  SET token = NULL 
                  WHERE token = :token';

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':token', $token);
            $stmt->execute();

            
            $success = $stmt->rowCount() > 0; 
        } catch (PDOException $e) {
            error_log("error de borrado");
        }

        return $success;
    }
}
