<?php

namespace App\core\data;

use App\core\model\Solicitud;

class solicitudRepo implements RepoInterface
{
    // CREATE
    public static function save($solicitud)
    {
        $conn = DBC::getConnection();
        $solicitudId = false;

        try {
            $conn->beginTransaction();

            $querySolicitud = 'INSERT INTO SOLICITUD
                           (alumno_id, oferta_id, finalizado)
                           VALUES (:alumno_id, :oferta_id, :finalizado)';
            $stmtSolicitud = $conn->prepare($querySolicitud);
            $stmtSolicitud->bindParam(':alumno_id', $solicitud->alumno->id);
            $stmtSolicitud->bindParam(':oferta_id', $solicitud->oferta->id);
            $stmtSolicitud->bindParam(':finalizado', $solicitud->finalizado);

            $stmtSolicitud->execute();

            $solicitudId = $conn->lastInsertId();

            $conn->commit();
        } catch (\PDOException $e) {
            $conn->rollBack();
            error_log("Error al insertar solicitud: " . $e->getMessage());
            $solicitudId = false;
        }

        return $solicitudId;
    }


    // READ
    public static function findAll()
    {
        $conn = DBC::getConnection();
        $solicitudes = [];

        $query = $conn->prepare(
            'SELECT s.id AS solicitud_id,
                s.fecha_solicitud,
                s.finalizado AS solicitud_finalizada,
                a.id AS alumno_id,
                a.nombre AS nombre_alumno,
                o.id AS oferta_id,
                o.titulo AS oferta_title
         FROM SOLICITUD s
         JOIN ALUMNO a ON s.alumno_id = a.id
         JOIN OFERTA o ON s.oferta_id = o.id'
        );

        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $res) {
            $alumno = new Alumno(
                $res['alumno_id'], // $id
                null,              // $username
                null,              // $password
                $res['nombre_alumno'], // $nombre
                null,              // $apellido
                null,              // $telefono
                null,              // $provincia
                null,              // $localidad
                null,              // $direccion
                null,              // $foto
                null               // $cv
            );

            $oferta = new Oferta(
                $res['oferta_id'],    // $id
                null,                 // $fechaCreacion
                null,                 // $fechaFin
                null,                 // $salario
                null,                 // $descripcion
                $res['oferta_title']  // $titulo
            );

            $solicitudes[] = new Solicitud(
                $res['solicitud_id'],              // $id
                $res['fecha_solicitud'],           // $fechaCreacion (pending CONVERTER)
                $alumno,                           // $alumno
                $oferta,                           // $oferta
                $res['solicitud_finalizada'] // $finalizado
            );
        }

        return $solicitudes;
    }

    public static function findById(int $id)
    {
        $conn = DBC::getConnection();
        $solicitud = null;

        $stmt = $conn->prepare(
            'SELECT s.id AS solicitud_id,
                s.fecha_solicitud,
                s.finalizado AS solicitud_finalizada,
                a.id AS alumno_id,
                a.nombre AS nombre_alumno,
                o.id AS oferta_id,
                o.titulo AS oferta_title
         FROM SOLICITUD s
         JOIN ALUMNO a ON s.alumno_id = a.id
         JOIN OFERTA o ON s.oferta_id = o.id
         WHERE s.id = :id'
        );

        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            $alumno = new Alumno(
                $res['alumno_id'],
                null,
                null,
                $res['nombre_alumno'],
                null,
                null,
                null,
                null,
                null,
                null,
                null
            );

            $oferta = new Oferta(
                $res['oferta_id'],
                null,
                null,
                null,
                null,
                $res['oferta_title']
            );

            $solicitud = new Solicitud(
                $res['solicitud_id'],
                $res['fecha_solicitud'], // pending CONVERTER
                $alumno,
                $oferta,
                $res['solicitud_finalizada']
            );
        }

        return $solicitud;
    }


    // UPDATE
    public static function updateById($solicitud)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare(
                'UPDATE SOLICITUD
             SET alumno_id = :alumno_id,
                 oferta_id = :oferta_id,
                 fecha_solicitud = :fecha_solicitud,
                 finalizado = :finalizado
             WHERE id = :id'
            );

            $stmt->bindParam(':alumno_id', $solicitud->alumno->id);
            $stmt->bindParam(':oferta_id', $solicitud->oferta->id);
            $stmt->bindParam(':fecha_solicitud', $solicitud->fechaCreacion);
            $stmt->bindParam(':finalizado', $solicitud->finalizado);
            $stmt->bindParam(':id', $solicitud->id);

            $stmt->execute();

            $conn->commit();
            $salida = true;
        } catch (\PDOException $e) {
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
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $conn->commit();
            $salida = true;
        } catch (\PDOException $e) {
            $conn->rollBack();
            error_log("Error al borrar solicitud: " . $e->getMessage());
            $salida = false;
        }

        return $salida;
    }
}
