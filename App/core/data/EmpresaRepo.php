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

            // 2. Insertar en EMPRESA
            $queryEmpresa = 'INSERT INTO EMPRESA 
                                (nombre, telefono, direccion, nombre_persona, telefono_persona, logo, user_id, validacion, provincia, localidad, cif)
                             VALUES 
                                (:nomb, :telef, :direccion, :nom_pers, :telf_pers, :logo, :user_id, :validacion, :provincia, :localidad, :cif)';
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
            $stmtEmpresa->bindValue(':cif', $empresa->cif);
            $stmtEmpresa->execute();

            $empresaId = $conn->lastInsertId();

            $queryToken = 'INSERT INTO USER_TOKEN (user_id, token) VALUES (:user_id, NULL)';
            $stmtToken = $conn->prepare($queryToken);
            $stmtToken->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmtToken->execute();

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error al guardar empresa: " . $e->getMessage());
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
                    e.localidad AS localidad,
                    e.cif AS cif
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
                $res['cif'],
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

    // READ ORDER BY
    public static function findAllOrderBy($filtro)
    {
        $conn = DBC::getConnection();
        $empresas = [];

        $allowedColumns = [
            'nombre'    => 'e.nombre',
            'email'  => 'u.user_name',
            'cif'       => 'e.cif',
            'telefono' => 'e.telefono',
        ];

        $orderBy = $allowedColumns[$filtro] ?? 'e.nombre';

        $sql = 'SELECT e.id AS empresa_id,
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
                    e.localidad AS localidad,
                    e.cif AS cif
             FROM EMPRESA e
             JOIN USER u ON e.user_id = u.id
             ORDER BY ' . $orderBy; 

        $query = $conn->prepare($sql);
        $query->execute();

        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $res) {
            $empresas[] = new Empresa(
                $res['empresa_id'],
                $res['username'],
                $res['pass'],
                $res['cif'],
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

    public static function filteredFindAll($string)
    {
        $conn = DBC::getConnection();
        $empresas = [];

        // Convertimos el string a minúsculas para la búsqueda
        $searchString = '%' . strtolower($string) . '%';

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
                    e.localidad AS localidad,
                    e.cif AS cif
            FROM EMPRESA e
            JOIN USER u ON e.user_id = u.id
            WHERE LOWER(e.nombre) LIKE :searchString
                OR LOWER(u.user_name) LIKE :searchString
                OR LOWER(e.cif) LIKE :searchString
                OR LOWER(e.telefono) LIKE :searchString'
        );

        // Solo necesitamos vincular el parámetro una vez, PDO lo usará en todos los lugares donde aparece :searchString
        $query->bindParam(':searchString', $searchString);
        $query->execute();

        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $res) {
            $empresas[] = new Empresa(
                $res['empresa_id'],
                $res['username'],
                $res['pass'],
                $res['cif'],
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
                         e.localidad AS localidad,
                         e.cif AS cif
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

        return new Empresa(
            $resultado['empresa_id'],
            $resultado['username'],
            $resultado['pass'],
            $resultado['cif'],
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

    public static function findByUserId(int $userId)
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
                         e.localidad AS localidad,
                         e.cif AS cif
                  FROM EMPRESA e
                  JOIN USER u ON e.user_id = u.id
                  WHERE e.user_id = :userIdValue';

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':userIdValue', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            $empresa = null;
        } else {
            $empresa = new Empresa(
                $resultado['empresa_id'],
                $resultado['username'],
                $resultado['pass'],
                $resultado['cif'],
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

        return $empresa;
    }

    public static function findByToken($token)
    {
        $conn = DBC::getConnection();
        $empresa = null;

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
                         e.localidad AS localidad,
                         e.cif AS cif
                  FROM EMPRESA e
                  JOIN USER u ON e.user_id = u.id
                  JOIN USER_TOKEN ut ON u.id = ut.user_id
                  WHERE ut.token = :token';

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':token', $token, PDO::PARAM_STR);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $empresa = new Empresa(
                    $resultado['empresa_id'],
                    $resultado['username'],
                    $resultado['pass'],
                    $resultado['cif'],
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
        } catch (PDOException $e) {
            error_log("Error al buscar Empresa por token: " . $e->getMessage());
        }

        return $empresa;
    }

    public static function findCompaniesWithMatchingOffers($alumnoId)
    {
        $conn = DBC::getConnection();
        $empresas = [];

        try {
            $query = $conn->prepare(
                'SELECT DISTINCT
                    e.id AS empresa_id,
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
                    e.localidad AS localidad,
                    e.cif AS cif
                 FROM EMPRESA e
                 JOIN USER u ON e.user_id = u.id
                 JOIN OFERTA o ON e.id = o.empresa_id
                 JOIN OFERTA_CICLO oc ON o.id = oc.oferta_id
                 JOIN ALUMNO_CICLO ac ON oc.ciclo_id = ac.ciclo_id
                 WHERE ac.alumno_id = :alumno_id'
            );

            $query->bindValue(':alumno_id', $alumnoId, PDO::PARAM_INT);
            $query->execute();
            $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $res) {
                $empresas[] = new Empresa(
                    $res['empresa_id'],
                    $res['username'],
                    $res['pass'],
                    $res['cif'],
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
        } catch (PDOException $e) {
            error_log("Error al buscar empresas con ofertas coincidentes para el alumno ID $alumnoId: " . $e->getMessage());
            return [];
        } catch (Exception $e) {
             error_log("Error general al buscar empresas con ofertas coincidentes: " . $e->getMessage());
             return [];
        }

        return $empresas;
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
                                 localidad = :localidad,
                                 cif = :cif
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
            $stmtEmpresa->bindValue(':cif', $empresa->cif);
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

    public static function isEmailTaken(string $email): bool
    {
        $conn = DBC::getConnection();

        $query = 'SELECT COUNT(id) FROM USER WHERE user_name = :email';
        
        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error de DB al verificar email: " . $e->getMessage());
            return true; 
        }
    }

    public static function isCifTaken(string $cif): bool
    {
        $conn = DBC::getConnection();

        $query = 'SELECT COUNT(id) FROM EMPRESA WHERE cif = :cif';

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':cif', $cif, PDO::PARAM_STR);
            $stmt->execute();

            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error de DB al verificar CIF: " . $e->getMessage());
            return true; 
        }
    }

    
}
