<?php

namespace App\core\data;

use App\core\model\Solicitud;
use App\core\data\DBC;
use App\core\data\RepoInterface;

use PDO;
use PDOException;
use Exception;

class solicitudRepo implements RepoInterface
{
    /**
     * @param Solicitud $solicitud
     * @return int|false
     */
    // CREATE
    // public static function save($solicitud)
    // {
    //     $conn = DBC::getConnection();
    //     $solicitudId = false;

    //     try {
    //         $conn->beginTransaction();

    //         $querySolicitud = 'INSERT INTO SOLICITUD
    //                        (alumno_id, oferta_id, comentarios, finalizado)
    //                        VALUES (:alumno_id, :oferta_id, :comentarios, :finalizado)';
    //         $stmtSolicitud = $conn->prepare($querySolicitud);
            
    //         // CORRECTO (alumnoId y ofertaId)
    //         $stmtSolicitud->bindValue(':alumno_id', $solicitud->alumnoId); 
    //         $stmtSolicitud->bindValue(':oferta_id', $solicitud->ofertaId); 
            
    //         $stmtSolicitud->bindValue(':comentarios', $solicitud->comentarios);
    //         $stmtSolicitud->bindValue(':finalizado', $solicitud->finalizado);

    //         $stmtSolicitud->execute();

    //         $solicitudId = $conn->lastInsertId();

    //         $conn->commit();
    //     } catch (\PDOException $e) {
    //         $conn->rollBack();
    //         echo "<pre>Error al insertar solicitud: " . $e->getMessage() . "</pre>";
    //         $solicitudId = false;
    //     }

    //     return $solicitudId;
    // }


    public static function save($solicitud)
    {
        error_log("=== SolicitudRepo::save START ===");
        error_log("Solicitud ID: " . ($solicitud->id ?? 'NULL'));
        error_log("Alumno ID: " . ($solicitud->alumnoId ?? 'NULL'));
        error_log("Oferta ID: " . ($solicitud->ofertaId ?? 'NULL'));
        error_log("Comentarios: " . ($solicitud->comentarios ?? 'NULL'));
        error_log("Finalizado: " . ($solicitud->finalizado ?? 'NULL'));
        
        $conn = DBC::getConnection();
        $solicitudId = false;

        try {
            $conn->beginTransaction();

            $querySolicitud = 'INSERT INTO SOLICITUD
                        (alumno_id, oferta_id, comentarios, finalizado)
                        VALUES (:alumno_id, :oferta_id, :comentarios, :finalizado)';
            
            $stmtSolicitud = $conn->prepare($querySolicitud);
            
            error_log("Binding values...");
            $stmtSolicitud->bindValue(':alumno_id', $solicitud->alumnoId, PDO::PARAM_INT); 
            $stmtSolicitud->bindValue(':oferta_id', $solicitud->ofertaId, PDO::PARAM_INT); 
            $stmtSolicitud->bindValue(':comentarios', $solicitud->comentarios, PDO::PARAM_STR);
            $stmtSolicitud->bindValue(':finalizado', $solicitud->finalizado, PDO::PARAM_INT);

            error_log("Executing query...");
            $stmtSolicitud->execute();

            $solicitudId = $conn->lastInsertId();
            error_log("Insert successful. New ID: " . $solicitudId);

            $conn->commit();
            error_log("Transaction committed");
            
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

        error_log("=== SolicitudRepo::save END (Result: " . ($solicitudId ? $solicitudId : 'FALSE') . ") ===");
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