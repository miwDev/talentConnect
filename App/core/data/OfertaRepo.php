<?php

namespace App\core\data;

use App\core\model\Oferta;
use PDO;
use PDOException;
use Exception;

class OfertaRepo implements RepoInterface
{
    // CREATE
    public static function save($oferta)
    {
        $conn = DBC::getConnection();
        $ofertaId = false;

        try {
            $conn->beginTransaction();

            $queryOferta = 'INSERT INTO OFERTA
                           (empresa_id, fecha_fin_oferta, salario, descripcion, titulo)
                           VALUES (:empresa_id, :fecha_fin, :salario, :descripcion, :titulo)';
            $stmtOferta = $conn->prepare($queryOferta);
            $stmtOferta->bindValue(':empresa_id', $oferta->empresa->id);
            $stmtOferta->bindValue(':fecha_fin', $oferta->fechaFin); // #TODO YYYY-MM-DD
            $stmtOferta->bindValue(':salario', $oferta->salario);
            $stmtOferta->bindValue(':descripcion', $oferta->descripcion);
            $stmtOferta->bindValue(':titulo', $oferta->titulo);

            $stmtOferta->execute();

            $ofertaId = $conn->lastInsertId();

            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error al insertar oferta $oferta: " . $e->getMessage());
            $ofertaId = false;
        }

        return $ofertaId;
    }

    // READ
    public static function findAll()
    {
        $conn = DBC::getConnection();
        $ofertas = [];

        $query = $conn->prepare(
            'SELECT o.id AS oferta_id,
                o.fecha_oferta AS fecha_creacion,
                o.fecha_fin_oferta AS fecha_fin,
                o.salario AS salario,
                o.descripcion AS descripcion,
                o.titulo AS titulo,
                e.id AS empresa_id,
                e.nombre AS empresa_nombre
         FROM OFERTA o
         JOIN EMPRESA e ON o.empresa_id = e.id'
        );

        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $res) {
            $empresa = new Empresa(
                $res['empresa_id'],  // $id
                null,                // $username
                null,                // $password
                $res['empresa_nombre'], // $nombre
                null,                // $telefono
                null,                // $direccion
                null,                // $provincia
                null,                // $localidad
                null,                // $nombrePersona
                null,                // $telPersona
                null,                // $logo
                null                 // $validacion
            );

            $ofertas[] = new Oferta(
                $res['oferta_id'],
                $empresa,
                $res['fecha_creacion'],
                $res['fecha_fin'],
                $res['salario'],
                $res['descripcion'],
                $res['titulo']
            );
        }

        return $ofertas;
    }


    public static function findById(int $id)
    {
        $conn = DBC::getConnection();

        $query = $conn->prepare(
            'SELECT o.id AS oferta_id,
                o.fecha_oferta AS fecha_creacion,
                o.fecha_fin_oferta AS fecha_fin,
                o.salario AS salario,
                o.descripcion AS descripcion,
                o.titulo AS titulo,
                e.id AS empresa_id,
                e.nombre AS empresa_nombre
         FROM OFERTA o
         JOIN EMPRESA e ON o.empresa_id = e.id
         WHERE o.id = :id'
        );

        $query->bindValue(':id', $id);
        $query->execute();

        $res = $query->fetch(PDO::FETCH_ASSOC);

        if (!$res) {
            $oferta = null;
        } else {
            $empresa = new Empresa(
                $res['empresa_id'], // id
                null,               // username
                null,               // password
                $res['empresa_nombre'], // nombre
                null,               // telefono
                null,               // direccion
                null,               // provincia
                null,               // localidad
                null,               // nombrePersona
                null,               // telPersona
                null,               // logo
                null                // validacion
            );

            $oferta = new Oferta(
                $res['oferta_id'],
                $empresa,
                $res['fecha_creacion'],
                $res['fecha_fin'],
                $res['salario'],
                $res['descripcion'],
                $res['titulo']
            );
        }

        return $oferta;
    }

    // UPDATE
    public static function updateById($oferta)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $query = $conn->prepare(
                'UPDATE OFERTA
             SET fecha_oferta = :fecha_creacion,
                 fecha_fin_oferta = :fecha_fin,
                 salario = :salario,
                 descripcion = :descripcion,
                 titulo = :titulo,
                 empresa_id = :empresa_id
             WHERE id = :id'
            );

            $query->bindValue(':fecha_creacion', $oferta->fechaCreacion);
            $query->bindValue(':fecha_fin', $oferta->fechaFin);
            $query->bindValue(':salario', $oferta->salario);
            $query->bindValue(':descripcion', $oferta->descripcion);
            $query->bindValue(':titulo', $oferta->titulo);
            $query->bindValue(':empresa_id', $oferta->empresa->id);
            $query->bindValue(':id', $oferta->id);

            $query->execute();

            $conn->commit();
            $salida = true;
        } catch (PDOException $e) {
            $conn->rollBack();
            $salida = false;
            error_log("Error al actualizar Oferta: " . $e->getMessage());
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

            $query = $conn->prepare('DELETE FROM OFERTA WHERE id = :id');
            $query->bindValue(':id', $id);
            $query->execute();

            $conn->commit();
            $salida = true;
        } catch (PDOException $e) {
            $conn->rollBack();
            $salida = false;
            error_log("Error al borrar Oferta: " . $e->getMessage());
        }

        return $salida;
    }
}
