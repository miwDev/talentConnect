<?php

namespace App\core\data;

use App\core\model\Alumno;
use App\core\DTO\AlumnoDTO;
use App\core\data\EstudiosRepo;
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
                        (nombre, apellido, telefono, direccion, foto, cv, user_id, provincia, localidad, dni, confirmed)
                        VALUES (:nombre, :apellido, :telefono, :direccion, :foto, :cv, :user_id, :provincia, :localidad, :dni, :confirmed)';
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
            $stmtAlumno->bindValue(':dni', $alumno->dni);
            $stmtAlumno->bindValue(':confirmed', $alumno->confirmed);
            $stmtAlumno->execute();

            $alumnoId = $conn->lastInsertId();

            $queryToken = 'INSERT INTO USER_TOKEN (user_id, token) VALUES (:user_id, NULL)';
            $stmtToken = $conn->prepare($queryToken);
            $stmtToken->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmtToken->execute();

            EstudiosRepo::saveEstudiosForAlumno($alumnoId, $alumno->estudios);
            
            $conn->commit();

        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error al guardar alumno: " . $e->getMessage());
            $alumnoId = false;
        }

        return $alumnoId;
    }


    public static function saveAll($alumnos)
    {
        $conn = DBC::getConnection();
        $guardados = [];
        $errores = [];

        try {
            $conn->beginTransaction();

            foreach ($alumnos as $index => $alumno) {

                $queryCheckEmail = 'SELECT COUNT(*) FROM USER WHERE user_name = :username';
                $stmtCheck = $conn->prepare($queryCheckEmail);
                $stmtCheck->bindValue(':username', $alumno->username);
                $stmtCheck->execute();
                $emailExists = $stmtCheck->fetchColumn() > 0;

                if ($emailExists) {
                    $errores[] = $alumno->username;
                    continue;
                }

                try {
                    $queryUser = 'INSERT INTO USER (user_name, passwrd, role_id)
                        VALUES (:username, :pass, :role_id)';
                    $stmtUser = $conn->prepare($queryUser);
                    $stmtUser->bindValue(':username', $alumno->username);
                    $stmtUser->bindValue(':pass', $alumno->password);
                    $stmtUser->bindValue(':role_id', 2);
                    $stmtUser->execute();

                    $userId = $conn->lastInsertId();

                    $queryAlumno = 'INSERT INTO ALUMNO 
                        (nombre, apellido, telefono, direccion, foto, cv, user_id, provincia, localidad, dni, confirmed)
                        VALUES (:nombre, :apellido, :telefono, :direccion, :foto, :cv, :user_id, :provincia, :localidad, :dni, :confirmed)';
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
                    $stmtAlumno->bindValue(':dni', $alumno->dni);
                    $stmtAlumno->bindValue(':confirmed', $alumno->confirmed);
                    $stmtAlumno->execute();

                    $alumnoId = $conn->lastInsertId();
                    $alumno->id = $alumnoId;

                    $queryToken = 'INSERT INTO USER_TOKEN (user_id, token) VALUES (:user_id, NULL)';
                    $stmtToken = $conn->prepare($queryToken);
                    $stmtToken->bindValue(':user_id', $userId, PDO::PARAM_INT);
                    $stmtToken->execute();

                    
                    EstudiosRepo::saveEstudiosForAlumno($alumnoId, $alumno->estudios);

                    $guardados[] = $alumno;
                } catch (Exception $e) {
                    error_log("Error al guardar alumno en índice $index: " . $e->getMessage());
                    $errores[] = $alumno->username;
                }
            }

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error en saveAll alumnos: " . $e->getMessage());
            $guardados = [];
            $errores = array_keys($alumnos);
        }

        return [
            'guardados' => $guardados,
            'errores' => $errores
        ];
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
                a.localidad AS localidad,
                a.dni as dni,
                a.confirmed as confirmed
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
                $res['dni'],
                $res['tel'],
                $res['direccion'],
                $res['foto'],
                $res['cv'],
                $res['provincia'],
                $res['localidad'],
                $res['confirmed']
            );
        }

        return $alumnos;
    }

    public static function findAllByOfertaId($ofertaId)
    {
        $conn = DBC::getConnection();
        $alumnos = [];

        try {
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
                    a.localidad AS localidad,
                    a.dni AS dni,
                    a.confirmed AS confirmed
                FROM ALUMNO a
                JOIN USER u ON a.user_id = u.id
                JOIN SOLICITUD s ON a.id = s.alumno_id
                WHERE s.oferta_id = :oferta_id'
            );

            $query->bindValue(':oferta_id', $ofertaId, PDO::PARAM_INT);
            $query->execute();
            $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

            $stmtCiclos = $conn->prepare(
                'SELECT c.nombre 
                FROM ALUMNO_CICLO ac
                JOIN CICLO c ON ac.ciclo_id = c.id
                WHERE ac.alumno_id = :alumno_id'
            );

            foreach ($resultados as $res) {
                $alumno = new Alumno(
                    $res['alumno_id'],
                    $res['username'],
                    $res['pass'],
                    $res['nombre'],
                    $res['ape'],
                    $res['dni'],
                    $res['tel'],
                    $res['direccion'],
                    $res['foto'],
                    $res['cv'],
                    $res['provincia'],
                    $res['localidad'],
                    $res['confirmed']
                );

                $stmtCiclos->bindValue(':alumno_id', $res['alumno_id'], PDO::PARAM_INT);
                $stmtCiclos->execute();
                
                $ciclosNombres = $stmtCiclos->fetchAll(PDO::FETCH_COLUMN);

                $alumno->estudios = $ciclosNombres; 

                $alumnos[] = $alumno;
            }

        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return [];
        }

        return $alumnos;
    }

    public static function findById($id)
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
                        a.localidad as localidad,
                        a.dni as dni,
                        a.confirmed as confirmed
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
                $resultado['dni'],
                $resultado['tel'],
                $resultado['direccion'],
                $resultado['foto'],
                $resultado['cv'],
                $resultado['provincia'],
                $resultado['localidad'],
                $resultado['confirmed']
            );
        }

        return $alumno;
    }

    public static function findByUserId($userId)
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
                        a.localidad as localidad,
                        a.dni as dni,
                        a.confirmed as confirmed
            FROM ALUMNO a
            JOIN USER u
            on a.user_id = u.id
            WHERE a.user_id = :userIdValue';

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':userIdValue', $userId, PDO::PARAM_INT);
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
                $resultado['dni'],
                $resultado['tel'],
                $resultado['direccion'],
                $resultado['foto'],
                $resultado['cv'],
                $resultado['provincia'],
                $resultado['localidad'],
                $resultado['confirmed']
            );
        }

        return $alumno;
    }
    
    public static function findByToken($token)
    {
        $conn = DBC::getConnection();
        $alumno = null;

        $query = 'SELECT a.id AS alumno_id,
                        u.user_name AS username,
                        u.passwrd AS pass,
                        a.nombre AS nombre,
                        a.apellido AS ape,
                        a.telefono AS tel,
                        a.direccion AS direccion,
                        a.foto AS foto,
                        a.cv AS cv,
                        a.provincia AS provincia,
                        a.localidad AS localidad,
                        a.dni AS dni,
                        a.confirmed AS confirmed
                FROM ALUMNO a
                JOIN USER u ON a.user_id = u.id
                JOIN USER_TOKEN ut ON u.id = ut.user_id
                WHERE ut.token = :token';

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':token', $token, PDO::PARAM_STR);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $alumno = new Alumno(
                    $resultado['alumno_id'],
                    $resultado['username'],
                    $resultado['pass'],
                    $resultado['nombre'],
                    $resultado['ape'],
                    $resultado['dni'],
                    $resultado['tel'],
                    $resultado['direccion'],
                    $resultado['foto'],
                    $resultado['cv'],
                    $resultado['provincia'],
                    $resultado['localidad'],
                    $resultado['confirmed']
                );
            }
        } catch (PDOException $e) {
            error_log("Error al buscar Alumno por token: " . $e->getMessage());
        }

        return $alumno;
    }


    public static function findDTOBySizePage($size, $page)
    {
        return true;
    }

    // UPDATE
    public static function update($alumno)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            // Get the user_id first using the alumno's id
            $queryGetUserId = 'SELECT user_id FROM ALUMNO WHERE id = :alumno_id';
            $stmtGetUserId = $conn->prepare($queryGetUserId);
            $stmtGetUserId->bindValue(':alumno_id', $alumno->id);
            $stmtGetUserId->execute();
            $user_id = $stmtGetUserId->fetchColumn();

            if (!$user_id) {
                throw new Exception("No se encontró el user_id para el alumno con id: " . $alumno->id);
            }

            // Update ALUMNO table
            $queryAlumno = 'UPDATE ALUMNO
                        SET nombre = :nombre,
                            apellido = :apellido,
                            telefono = :telefono,
                            direccion = :direccion,
                            foto = :foto,
                            cv = :cv,
                            provincia = :provincia,
                            localidad = :localidad,
                            dni = :dni,
                            confirmed = :confirmed
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
            $stmtAlumno->bindValue(':dni', $alumno->dni);
            $stmtAlumno->bindValue(':confirmed', $alumno->confirmed);
            $stmtAlumno->bindValue(':id', $alumno->id);
            $stmtAlumno->execute();

            // Update USER table using the retrieved user_id
            $queryUser = 'UPDATE USER
                SET user_name = :username,
                    passwrd = :pass
                WHERE id = :user_id';

            $stmtUser = $conn->prepare($queryUser);
            $stmtUser->bindValue(':username', $alumno->username);
            $stmtUser->bindValue(':pass', $alumno->password);
            $stmtUser->bindValue(':user_id', $user_id);
            $stmtUser->execute();

            $conn->commit();
            $salida = true;
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error al actualizar Alumno y User: " . $e->getMessage());
            $salida = false;
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error: " . $e->getMessage());
            $salida = false;
        }

        return $salida;
    }


    public static function updateDTO($alumnoDTO)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $queryAlumno = 'UPDATE ALUMNO
                        SET nombre = :nombre,
                            apellido = :apellido
                        WHERE id = :id';

            $stmtAlumno = $conn->prepare($queryAlumno);
            $stmtAlumno->bindValue(':nombre', $alumnoDTO->nombre);
            $stmtAlumno->bindValue(':apellido', $alumnoDTO->apellido);
            $stmtAlumno->bindValue(':id', $alumnoDTO->id);
            $stmtAlumno->execute();

            $queryGetUserId = 'SELECT user_id FROM ALUMNO WHERE id = :alumno_id';
            $stmtGetUserId = $conn->prepare($queryGetUserId);
            $stmtGetUserId->bindValue(':alumno_id', $alumnoDTO->id);
            $stmtGetUserId->execute();
            $user_id = $stmtGetUserId->fetchColumn();

            if ($user_id) {

                $queryUser = 'UPDATE USER
                    SET user_name = :username
                    WHERE id = :user_id';

                $stmtUser = $conn->prepare($queryUser);
                $stmtUser->bindValue(':username', $alumnoDTO->email);
                $stmtUser->bindValue(':user_id', $user_id);
                $stmtUser->execute();
            }

            $conn->commit();
            $salida = true;
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error al actualizar Alumno DTO: " . $e->getMessage());
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
                $stmtToken = $conn->prepare('DELETE FROM USER_TOKEN WHERE user_id = :user_id');
                $stmtToken->bindValue(':user_id', $user_id);
                $stmtToken->execute();

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
            error_log("Error de DB al verificar email de Alumno: " . $e->getMessage());
            return true; 
        }
    }

    public static function isDniTaken(string $dni): bool
    {
        $conn = DBC::getConnection();

        $query = 'SELECT COUNT(id) FROM ALUMNO WHERE dni = :dni';

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':dni', $dni, PDO::PARAM_STR);
            $stmt->execute();

            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error de DB al verificar DNI de Alumno: " . $e->getMessage());
            return true; 
        }
    }
    
}