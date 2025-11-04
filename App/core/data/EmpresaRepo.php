<?php

namespace App\core\data;

use App\core\model\Empresa;
use PDO;
use PDOException;
use Exception;

class EmpresaRepo implements RepoInterface
{
    // CREATE
    public static function save($empresa)
    {
        $conn = DBC::getConnection();
        $empresaId = false;

        try {
            $conn->beginTransaction();

            $queryUser = 'INSERT INTO USER (user_name, passwrd, role_id)
                          VALUES (:username, :pass, :role_id)';
            $stmtUser = $conn->prepare($queryUser);
            $stmtUser->bindValue(':username', $empresa->username);
            $stmtUser->bindValue(':pass', $empresa->password);
            $stmtUser->bindValue(':role_id', 3);
            $stmtUser->execute();

            $userId = $conn->lastInsertId();

            $queryEmpresa = 'INSERT INTO EMPRESA 
                                (nombre, telefono, direccion, nombre_persona, telefono_persona, logo, user_id, validacion, provincia, localidad)
                             VALUES 
                                (:nomb, :telef, :direccion, :nom_pers, :telf_pers, :logo, :user_id, :validacion, :provincia, :localidad)';
            $stmtEmpresa = $conn->prepare($queryEmpresa);
            $stmtEmpresa->bindValue(':nomb', $empresa->nombre);
            $stmtEmpresa->bindValue(':telef', $empresa->telefono);
            $stmtEmpresa->bindValue(':direccion', $empresa->direccion);
            $stmtEmpresa->bindValue(':nom_pers', $empresa->nombrePersona);
            $stmtEmpresa->bindValue(':telf_pers', $empresa->telPersona);
            $stmtEmpresa->bindValue(':logo', $empresa->logo);
            $stmtEmpresa->bindValue(':user_id', $userId);
            $stmtEmpresa->bindValue(':provincia', $empresa->provincia);
            $stmtEmpresa->bindValue(':localidad', $empresa->localidad);
            $stmtEmpresa->bindValue(':validacion', $empresa->validacion);
            $stmtEmpresa->execute();

            $empresaId = $conn->lastInsertId();
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            $empresaId = false;
        }

        return $empresaId;
    }

    // READ
    public static function findAll()
    {
        $conn = DBC::getConnection();
        $empresas = [];

        $query = $conn->prepare(
            'SELECT e.id AS empresa_id,
                    u.user_name AS username,
                    u.passwrd AS pass,
                    e.nombre AS nombre,
                    e.telefono AS telefono,
                    e.direccion AS direccion,
                    e.nombre_persona AS nombrePersona,
                    e.telefono_persona AS telPersona,
                    e.logo AS logo,
                    e.validacion AS validacion,
                    e.provincia AS provincia,
                    e.localidad AS localidad
             FROM EMPRESA e
             JOIN USER u ON e.user_id = u.id'
        );

        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $res) {
            // Constructor order: id, username, pass, nombre, telefono, direccion, provincia, localidad, nombrePersona, telPersona, logo, validacion
            $empresas[] = new Empresa(
                $res['empresa_id'],
                $res['username'],
                $res['pass'],
                $res['nombre'],
                $res['telefono'],
                $res['direccion'],
                $res['provincia'],
                $res['localidad'],
                $res['nombrePersona'],
                $res['telPersona'],
                $res['logo'],
                $res['validacion']
            );
        }

        return $empresas;
    }

    public static function findById(int $id)
    {
        $conn = DBC::getConnection();

        $query = 'SELECT e.id AS empresa_id,
                         u.user_name AS username,
                         u.passwrd AS pass,
                         e.nombre AS nombre,
                         e.telefono AS telefono,
                         e.direccion AS direccion,
                         e.nombre_persona AS nombrePersona,
                         e.telefono_persona AS telPersona,
                         e.logo AS logo,
                         e.validacion AS validacion,
                         e.provincia AS provincia,
                         e.localidad AS localidad
                  FROM EMPRESA e
                  JOIN USER u ON e.user_id = u.id
                  WHERE e.id = :id';

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            return null;
        }

        // Constructor order: id, username, pass, nombre, telefono, direccion, provincia, localidad, nombrePersona, telPersona, logo, validacion
        return new Empresa(
            $resultado['empresa_id'],
            $resultado['username'],
            $resultado['pass'],
            $resultado['nombre'],
            $resultado['telefono'],
            $resultado['direccion'],
            $resultado['provincia'],
            $resultado['localidad'],
            $resultado['nombrePersona'],
            $resultado['telPersona'],
            $resultado['logo'],
            $resultado['validacion']
        );
    }

    public static function findBySizePage($size, $page)
    {
        return true;
    }

    // UPDATE
    public static function update($empresa)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $queryEmpresa = 'UPDATE EMPRESA
                             SET nombre = :nomb,
                                 telefono = :telef,
                                 direccion = :direccion,
                                 nombre_persona = :nom_pers,
                                 telefono_persona = :telf_pers,
                                 logo = :logo,
                                 validacion = :validacion,
                                 provincia = :provincia,
                                 localidad = :localidad
                             WHERE id = :id';
            $stmtEmpresa = $conn->prepare($queryEmpresa);
            $stmtEmpresa->bindValue(':nomb', $empresa->nombre);
            $stmtEmpresa->bindValue(':telef', $empresa->telefono);
            $stmtEmpresa->bindValue(':direccion', $empresa->direccion);
            $stmtEmpresa->bindValue(':nom_pers', $empresa->nombrePersona);
            $stmtEmpresa->bindValue(':telf_pers', $empresa->telPersona);
            $stmtEmpresa->bindValue(':logo', $empresa->logo);
            $stmtEmpresa->bindValue(':validacion', $empresa->validacion);
            $stmtEmpresa->bindValue(':provincia', $empresa->provincia);
            $stmtEmpresa->bindValue(':localidad', $empresa->localidad);
            $stmtEmpresa->bindValue(':id', $empresa->id);
            $stmtEmpresa->execute();

            $queryGetUserId = 'SELECT user_id FROM EMPRESA WHERE id = :empresa_id';
            $stmtGetUserId = $conn->prepare($queryGetUserId);
            $stmtGetUserId->bindValue(':empresa_id', $empresa->id);
            $stmtGetUserId->execute();
            $user_id = $stmtGetUserId->fetchColumn();

            if (!$user_id) {
                throw new \PDOException("No se pudo recuperar el user_id.");
            }

            $queryUser = 'UPDATE USER
                          SET user_name = :username,
                              passwrd = :pass
                          WHERE id = :user_id';
            $stmtUser = $conn->prepare($queryUser);
            $stmtUser->bindValue(':username', $empresa->username);
            $stmtUser->bindValue(':pass', $empresa->password);
            $stmtUser->bindValue(':user_id', $user_id);
            $stmtUser->execute();

            $conn->commit();
            $salida = true;
        } catch (\PDOException $e) {
            $conn->rollBack();
            $salida = false;
        }

        return $salida;
    }

    // DELETE
    public static function deleteById($empresa_id)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare('SELECT user_id FROM EMPRESA WHERE id = :empresa_id');
            $stmt->bindValue(':empresa_id', $empresa_id);
            $stmt->execute();
            $user_id = $stmt->fetchColumn();

            if ($user_id) {
                $stmtUser = $conn->prepare('DELETE FROM USER WHERE id = :user_id');
                $stmtUser->bindValue(':user_id', $user_id);
                $stmtUser->execute();

                $conn->commit();
                $salida = true;
            } else {
                $conn->rollBack();
                $salida = false;
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            $salida = false;
        }

        return $salida;
    }
}
