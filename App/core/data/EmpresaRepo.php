<?php

namespace App\core\data;

use App\core\model\Empresa;

class EmpresaRepo implements RepoInterface
{
    // CREATE
    public static function save($empresa)
    {
        $conn = DBC::getConnection();
        $empresaId = false;

        try {
            $conn->beginTransaction();

            $queryUser = 'INSERT INTO USER (user_name, password, role_id)
                          VALUES (:username, :pass, :role_id)';
            $stmtUser = $conn->prepare($queryUser);
            $stmtUser->bindParam(':username', $empresa->username);
            $stmtUser->bindParam(':pass', $empresa->password);
            $stmtUser->bindValue(':role_id', 3);
            $stmtUser->execute();

            $userId = $conn->lastInsertId();
            $empresa->user_id = $userId;

            $queryEmpresa = 'INSERT INTO EMPRESA 
                                (nomb, telef, direccion, nombre_pers, tel_pers, logo, user_id, validacion, provincia, localidad)
                             VALUES 
                                (:nomb, :telef, :direccion, :nom_pers, :telf_pers, :logo, :user_id, :validacion, :provincia, :localidad)';
            $stmtEmpresa = $conn->prepare($queryEmpresa);
            $stmtEmpresa->bindParam(':nomb', $empresa->nombre);
            $stmtEmpresa->bindParam(':telef', $empresa->telefono);
            $stmtEmpresa->bindParam(':direccion', $empresa->direccion);
            $stmtEmpresa->bindParam(':nom_pers', $empresa->nombrePersona);
            $stmtEmpresa->bindParam(':telf_pers', $empresa->telPersona);
            $stmtEmpresa->bindParam(':logo', $empresa->logo);
            $stmtEmpresa->bindParam(':user_id', $empresa->user_id);
            $stmtEmpresa->bindParam(':provincia', $empresa->provincia);
            $stmtEmpresa->bindParam(':localidad', $empresa->localidad);
            $stmtEmpresa->bindParam(':validacion', $empresa->validacion);
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
                    u.password AS pass,
                    e.nomb AS nombre,
                    e.telef AS telefono,
                    e.direccion AS direccion,
                    e.nombre_pers AS nombrePersona,
                    e.tel_pers AS telPersona,
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
            $empresas[] = new Empresa(
                $res['empresa_id'],
                $res['username'],
                $res['pass'],
                $res['nombre'],
                $res['telefono'],
                $res['direccion'],
                $res['nombrePersona'],
                $res['telPersona'],
                $res['logo'],
                $res['validacion'],
                $res['provincia'],
                $res['localidad']
            );
        }

        return $empresas;
    }

    public static function findById(int $id)
    {
        $conn = DBC::getConnection();

        $query = 'SELECT e.id AS empresa_id,
                         u.user_name AS username,
                         u.password AS pass,
                         e.nomb AS nombre,
                         e.telef AS telefono,
                         e.direccion AS direccion,
                         e.nombre_pers AS nombrePersona,
                         e.tel_pers AS telPersona,
                         e.logo AS logo,
                         e.validacion AS validacion,
                         e.provincia AS provincia,
                         e.localidad AS localidad
                  FROM EMPRESA e
                  JOIN USER u ON e.user_id = u.id
                  WHERE e.id = :id';

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            return null;
        }

        return new Empresa(
            $resultado['empresa_id'],
            $resultado['username'],
            $resultado['pass'],
            $resultado['nombre'],
            $resultado['telefono'],
            $resultado['direccion'],
            $resultado['nombrePersona'],
            $resultado['telPersona'],
            $resultado['logo'],
            $resultado['validacion'],
            $resultado['provincia'],
            $resultado['localidad']
        );
    }

    // UPDATE
    public static function updateById($empresa)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $queryEmpresa = 'UPDATE EMPRESA
                             SET nomb = :nomb,
                                 telef = :telef,
                                 direccion = :direccion,
                                 nombre_pers = :nom_pers,
                                 tel_pers = :telf_pers,
                                 logo = :logo,
                                 validacion = :validacion,
                                 provincia = :provincia,
                                 localidad = :localidad
                             WHERE id = :id';
            $stmtEmpresa = $conn->prepare($queryEmpresa);
            $stmtEmpresa->bindParam(':nomb', $empresa->nombre);
            $stmtEmpresa->bindParam(':telef', $empresa->telefono);
            $stmtEmpresa->bindParam(':direccion', $empresa->direccion);
            $stmtEmpresa->bindParam(':nom_pers', $empresa->nombrePersona);
            $stmtEmpresa->bindParam(':telf_pers', $empresa->telPersona);
            $stmtEmpresa->bindParam(':logo', $empresa->logo);
            $stmtEmpresa->bindParam(':validacion', $empresa->validacion);
            $stmtEmpresa->bindParam(':provincia', $empresa->provincia);
            $stmtEmpresa->bindParam(':localidad', $empresa->localidad);
            $stmtEmpresa->bindParam(':id', $empresa->id);
            $stmtEmpresa->execute();

            $queryUser = 'UPDATE USER
                          SET user_name = :username,
                              password = :pass
                          WHERE id = :user_id';
            $stmtUser = $conn->prepare($queryUser);
            $stmtUser->bindParam(':username', $empresa->username);
            $stmtUser->bindParam(':pass', $empresa->password);
            $stmtUser->bindParam(':user_id', $empresa->user_id);
            $stmtUser->execute();

            $conn->commit();
            $salida = true;
        } catch (PDOException $e) {
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
            $stmt->bindParam(':empresa_id', $empresa_id);
            $stmt->execute();
            $user_id = $stmt->fetchColumn();

            if ($user_id) {
                $stmtUser = $conn->prepare('DELETE FROM USER WHERE id = :user_id');
                $stmtUser->bindParam(':user_id', $user_id);
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
