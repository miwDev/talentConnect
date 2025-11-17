<?php

namespace App\core\data;

use App\core\model\Solicitud;
use App\core\data\DBC;
use App\core\data\RepoInterface;
use App\core\data\AlumnoRepo;
use App\core\model\Alumno;

use PDO;
use PDOException;
use Exception;

class SolicitudRepo implements RepoInterface
{

    public static function save($solicitud)
    {
       
        $conn = DBC::getConnection();
        $solicitudId = false;

        try {
            $conn->beginTransaction();

            $querySolicitud = 'INSERT INTO SOLICITUD
                        (alumno_id, oferta_id, comentarios, finalizado)
                        VALUES (:alumno_id, :oferta_id, :comentarios, :finalizado)';
            
            $stmtSolicitud = $conn->prepare($querySolicitud);
            
            $stmtSolicitud->bindValue(':alumno_id', $solicitud->alumnoId, PDO::PARAM_INT); 
            $stmtSolicitud->bindValue(':oferta_id', $solicitud->ofertaId, PDO::PARAM_INT); 
            $stmtSolicitud->bindValue(':comentarios', $solicitud->comentarios, PDO::PARAM_STR);
            $stmtSolicitud->bindValue(':finalizado', $solicitud->finalizado, PDO::PARAM_INT);

            $stmtSolicitud->execute();

            $solicitudId = $conn->lastInsertId();

            $conn->commit();
            
        } catch (\PDOException $e) {
            $conn->rollBack();
            error_log("PDO ERROR: " . $e->getMessage());
            error_log("SQL State: " . $e->getCode());
            $solicitudId = false;
        } catch (\Exception $e) {
            $conn->rollBack();
            error_log("GENERAL ERROR: " . $e->getMessage());
            $solicitudId = false;
        }
        return $solicitudId;
    }


    // READ
    public static function findAll()
    {
        $conn = DBC::getConnection();
        $solicitudes = [];

        // NO REQUIERE JOIN, SOLO RECUPERA IDS
        $query = $conn->prepare(
            'SELECT s.id AS solicitud_id,
                s.fecha_solicitud,
                s.comentarios AS solicitud_comentarios,
                s.finalizado AS solicitud_finalizada,
                s.alumno_id,
                s.oferta_id
         FROM SOLICITUD s'
        );

        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $res) {
            // Se asume que Solicitud espera los IDs directamente
            $solicitudes[] = new Solicitud(
                $res['solicitud_id'],              // $id
                $res['fecha_solicitud'],           // $fechaCreacion
                $res['alumno_id'],                 // $alumnoId
                $res['oferta_id'],                 // $ofertaId
                $res['solicitud_comentarios'],     // $comentarios
                $res['solicitud_finalizada']       // $finalizado
            );
        }

        return $solicitudes;
    }

    public static function findAllByAlumnoId(int $alumnoId)
    {
        $conn = DBC::getConnection();
        $solicitudes = [];

        try {
            $stmt = $conn->prepare(
                'SELECT s.id AS solicitud_id,
                    s.fecha_solicitud,
                    s.comentarios AS solicitud_comentarios,
                    s.finalizado AS solicitud_finalizada,
                    s.alumno_id,
                    s.oferta_id
                 FROM SOLICITUD s
                 WHERE s.alumno_id = :alumno_id'
            );

            $stmt->bindValue(':alumno_id', $alumnoId, PDO::PARAM_INT);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $res) {
                $solicitudes[] = new Solicitud(
                    $res['solicitud_id'],
                    $res['fecha_solicitud'],
                    $res['alumno_id'],
                    $res['oferta_id'],
                    $res['solicitud_comentarios'],
                    $res['solicitud_finalizada']
                );
            }

        } catch (PDOException $e) {
            error_log("Error al buscar solicitudes por alumno ID $alumnoId: " . $e->getMessage());
            return [];
        } catch (Exception $e) {
             error_log("Error general al buscar solicitudes: " . $e->getMessage());
             return [];
        }

        return $solicitudes;
    }

    public static function findById(int $id)
    {
        $conn = DBC::getConnection();
        $solicitud = null;

        // NO REQUIERE JOIN, SOLO RECUPERA IDS
        $stmt = $conn->prepare(
            'SELECT s.id AS solicitud_id,
                s.fecha_solicitud,
                s.comentarios AS solicitud_comentarios,
                s.finalizado AS solicitud_finalizada,
                s.alumno_id,
                s.oferta_id
         FROM SOLICITUD s
         WHERE s.id = :id'
        );

        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            // Se asume que Solicitud espera los IDs directamente
            $solicitud = new Solicitud(
                $res['solicitud_id'],
                $res['fecha_solicitud'],
                $res['alumno_id'],   // $alumnoId
                $res['oferta_id'],   // $ofertaId
                $res['solicitud_comentarios'],
                $res['solicitud_finalizada']
            );
        }

        return $solicitud;
    }

    /**
     * Busca una solicitud específica basada en el ID de la oferta y el ID del alumno.
     *
     * @param int $ofertaId
     * @param int $alumnoId
     * @return Solicitud|null
     */
    public static function findByOfertaAndAlumno($ofertaId, $alumnoId)
    {
        $conn = DBC::getConnection();
        $solicitud = null;

        // Buscamos la solicitud específica donde coincidan el alumno y la oferta
        $query = 'SELECT id AS solicitud_id, 
                         fecha_solicitud, 
                         alumno_id, 
                         oferta_id, 
                         comentarios AS solicitud_comentarios, 
                         finalizado AS solicitud_finalizada
                  FROM SOLICITUD 
                  WHERE oferta_id = :oferta_id 
                  AND alumno_id = :alumno_id';

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':oferta_id', $ofertaId, PDO::PARAM_INT);
            $stmt->bindValue(':alumno_id', $alumnoId, PDO::PARAM_INT);
            $stmt->execute();

            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res) {
                // Constructor consistente con los otros métodos find
                $solicitud = new Solicitud(
                    $res['solicitud_id'],
                    $res['fecha_solicitud'],
                    $res['alumno_id'],
                    $res['oferta_id'],
                    $res['solicitud_comentarios'],
                    $res['solicitud_finalizada']
                );
            }
        } catch (PDOException $e) {
            error_log("Error al buscar la solicitud específica (Oferta: $ofertaId, Alumno: $alumnoId): " . $e->getMessage());
        }

        return $solicitud;
    }

    /**
     * Obtiene el objeto Alumno completo asociado a una Solicitud.
     * * Patrón de Colaboración: SolicitudRepo encuentra la clave (alumno_id) 
     * y delega la construcción del objeto Alumno al AlumnoRepo.
     *
     * @param int $solicitudId
     * @return Alumno|null
     */
    public static function getAlumnoBySolicitudId(int $solicitudId): ?Alumno
    {
        $conn = DBC::getConnection();
        
        try {
            // 1. Obtener el alumno_id a partir del ID de la solicitud
            $stmt = $conn->prepare('SELECT alumno_id FROM SOLICITUD WHERE id = :solicitud_id');
            $stmt->bindValue(':solicitud_id', $solicitudId, PDO::PARAM_INT);
            $stmt->execute();
            $alumnoId = $stmt->fetchColumn();

            if (!$alumnoId) {
                return null; // La solicitud no existe o no tiene alumno asociado
            }

            // 2. Delegar la construcción del objeto Alumno al AlumnoRepo
            // Se asume que AlumnoRepo tiene un método findById o findByAlumnoId (usaremos findById)
            // Nota: Si usas findByUserId en AlumnoRepo, necesitarías primero la user_id
            return AlumnoRepo::findById($alumnoId);

        } catch (PDOException $e) {
            error_log("Error al obtener alumno por Solicitud ID $solicitudId: " . $e->getMessage());
            return null;
        }
    }


    /**
     * @param Solicitud $solicitud
     * @return bool
     */
    // UPDATE
    public static function update($solicitud)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare(
                'UPDATE SOLICITUD
             SET alumno_id = :alumno_id,
                 oferta_id = :oferta_id,
                 comentarios = :comentarios,
                 fecha_solicitud = :fecha_solicitud,
                 finalizado = :finalizado
             WHERE id = :id'
            );

            // CORREGIDO: Usamos alumnoId y ofertaId para consistencia con save()
            $stmt->bindValue(':alumno_id', $solicitud->alumnoId);
            $stmt->bindValue(':oferta_id', $solicitud->ofertaId);
            
            $stmt->bindValue(':comentarios', $solicitud->comentarios);
            $stmt->bindValue(':fecha_solicitud', $solicitud->fechaCreacion);
            $stmt->bindValue(':finalizado', $solicitud->finalizado);
            $stmt->bindValue(':id', $solicitud->id);

            $stmt->execute();

            $conn->commit();
            $salida = true;
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error al actualizar solicitud: " . $e->getMessage());
            $salida = false;
        }

        return $salida;
    }

    // DELETE
    public static function deleteById($id)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare('DELETE FROM SOLICITUD WHERE id = :id');
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $conn->commit();
            $salida = true;
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error al borrar solicitud: " . $e->getMessage());
            $salida = false;
        }

        return $salida;
    }
}