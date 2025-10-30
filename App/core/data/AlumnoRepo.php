<?php

namespace App\core\data;

use App\core\model\Alumno;
use PDO;
use PDOException;
use Exception;

class AlumnoRepo implements RepoInterface
{
    // CREATE
    public static function save($alumno)
    {
        $conn = DBC::getConnection();
        $alumnoId = false;

        try {
            $conn->beginTransaction();

            $queryUser = 'INSERT INTO USER (user_name, passwrd, role_id)
                      VALUES (:username, :pass, :role_id)';
            $stmtUser = $conn->prepare($queryUser);
            $stmtUser->bindValue(':username', $alumno->username);
            $stmtUser->bindValue(':pass', $alumno->password);
            $stmtUser->bindValue(':role_id', 2);
            $stmtUser->execute();

            $userId = $conn->lastInsertId();

            $queryAlumno = 'INSERT INTO ALUMNO 
                        (nombre, apellido, telefono, direccion, foto, cv, user_id, provincia, localidad)
                        VALUES (:nombre, :apellido, :telefono, :direccion, :foto, :cv, :user_id, :provincia, :localidad)';
            $stmtAlumno = $conn->prepare($queryAlumno);
            $stmtAlumno->bindValue(':nombre', $alumno->nombre);
            $stmtAlumno->bindValue(':apellido', $alumno->apellido);
            $stmtAlumno->bindValue(':telefono', $alumno->telefono);
            $stmtAlumno->bindValue(':direccion', $alumno->direccion);
            $stmtAlumno->bindValue(':foto', $alumno->foto);
            $stmtAlumno->bindValue(':cv', $alumno->cv);
            $stmtAlumno->bindValue(':user_id', $userId);
            $stmtAlumno->bindValue(':provincia', $alumno->provincia);
            $stmtAlumno->bindValue(':localidad', $alumno->localidad);
            $stmtAlumno->execute();

            $alumnoId = $conn->lastInsertId();

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error al guardar alumno: " . $e->getMessage());
            $alumnoId = false;
        }

        return $alumnoId;
    }

    // READ
    public static function findAll()
    {
        $conn = DBC::getConnection();
        $alumnos = [];

        $query = $conn->prepare(
            'SELECT a.id AS alumno_id,
                u.user_name AS username,
                u.passwrd AS pass,
                a.nombre AS nombre,
                a.apellido AS ape,
                a.telefono AS tel,
                a.direccion AS direccion,
                a.foto AS foto,
                a.cv AS cv,
                a.provincia AS provincia,
                a.localidad AS localidad
         FROM ALUMNO a
         JOIN USER u ON a.user_id = u.id'
        );

        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $res) {
            $alumnos[] = new Alumno(
                $res['alumno_id'],
                $res['username'],
                $res['pass'],
                $res['nombre'],
                $res['ape'],
                $res['tel'],
                $res['direccion'],
                $res['foto'],
                $res['cv'],
                $res['provincia'],
                $res['localidad']
            );
        }

        return $alumnos;
    }

    public static function findById(int $id)
    {
        $conn = DBC::getConnection();

        $query = 'SELECT a.id as alumno_id,
                        u.user_name as username,
                        u.passwrd as pass,
                        a.nombre as nombre,
                        a.apellido as ape,
                        a.telefono as tel,
                        a.direccion as direccion,
                        a.foto as foto,
                        a.cv as cv,
                        a.provincia as provincia,
                        a.localidad as localidad
            FROM ALUMNO a
            JOIN USER u
            on a.user_id = u.id
            WHERE a.id = :value';

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':value', $id, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            $alumno = null;
        } else {
            $alumno = new Alumno(
                $resultado['alumno_id'],
                $resultado['username'],
                $resultado['pass'],
                $resultado['nombre'],
                $resultado['ape'],
                $resultado['tel'],
                $resultado['direccion'],
                $resultado['foto'],
                $resultado['cv'],
                $resultado['provincia'],
                $resultado['localidad']
            );
        }

        return $alumno;
    }

    // UPDATE
    public static function updateById($alumno)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $queryAlumno = 'UPDATE ALUMNO
                            SET nombre = :nombre,
                                apellido = :apellido,
                                telefono = :telefono,
                                direccion = :direccion,
                                foto = :foto,
                                cv = :cv,
                                provincia = :provincia,
                                localidad = :localidad
                            WHERE id = :id';

            $stmtAlumno = $conn->prepare($queryAlumno);
            $stmtAlumno->bindValue(':nombre', $alumno->nombre);
            $stmtAlumno->bindValue(':apellido', $alumno->apellido);
            $stmtAlumno->bindValue(':telefono', $alumno->telefono);
            $stmtAlumno->bindValue(':direccion', $alumno->direccion);
            $stmtAlumno->bindValue(':foto', $alumno->foto);
            $stmtAlumno->bindValue(':cv', $alumno->cv);
            $stmtAlumno->bindValue(':provincia', $alumno->provincia);
            $stmtAlumno->bindValue(':localidad', $alumno->localidad);
            $stmtAlumno->bindValue(':id', $alumno->id);
            $stmtAlumno->execute();

            $queryUser = 'UPDATE USER
                    SET user_name = :username,
                        passwrd = :pass
                    WHERE id = :user_id';

            $stmtUser = $conn->prepare($queryUser);
            $stmtUser->bindValue(':username', $alumno->username);
            $stmtUser->bindValue(':pass', $alumno->pass);
            $stmtUser->bindValue(':user_id', $alumno->user_id);
            $stmtUser->execute();

            $conn->commit();

            $salida = true;
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error al actualizar Alumno y User: " . $e->getMessage());
            $salida = false;
        }

        return $salida;
    }

    // DELETE
    public static function deleteById($alumno_id)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare('SELECT user_id FROM ALUMNO WHERE id = :alumno_id');
            $stmt->bindValue(':alumno_id', $alumno_id);
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
            error_log("Error al borrar Alumno: " . $e->getMessage());
            $salida = false;
        }

        return $salida;
    }
}
