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
            $stmtOferta->bindValue(':empresa_id', $oferta->empresaId, PDO::PARAM_INT);
            $stmtOferta->bindValue(':fecha_fin', $oferta->fechaFin);
            $stmtOferta->bindValue(':salario', $oferta->salario);
            $stmtOferta->bindValue(':descripcion', $oferta->descripcion);
            $stmtOferta->bindValue(':titulo', $oferta->titulo);
            $stmtOferta->execute();

            $ofertaId = $conn->lastInsertId();
            $queryCiclo = 'INSERT INTO OFERTA_CICLO
                            (oferta_id, ciclo_id)
                            VALUES (:oferta_id, :ciclo_id)';
            $stmtCiclo = $conn->prepare($queryCiclo);

            foreach ($oferta->ciclos as $index => $cicloId) {

                // Ignorar valores vacíos o inválidos
                if (!empty($cicloId) && is_numeric($cicloId)) {
                    $cicloIdInt = (int)$cicloId;

                    $stmtCiclo->bindValue(':oferta_id', $ofertaId, PDO::PARAM_INT);
                    $stmtCiclo->bindValue(':ciclo_id', $cicloIdInt, PDO::PARAM_INT);

                    $stmtCiclo->execute();
                } else {
                    error_log("❌ Ciclo NO insertado - valor: '$cicloId'");
                }
            }

            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("❌ Error al insertar oferta: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $ofertaId = false;
        }

        return $ofertaId;
    }

    // READ
    public static function findAll()
    {
        $conn = DBC::getConnection();
        $ofertas = [];

        // 1. Obtener todas las ofertas
        $query = $conn->prepare(
            'SELECT o.id AS oferta_id,
                o.fecha_oferta AS fecha_creacion,
                o.fecha_fin_oferta AS fecha_fin,
                o.salario AS salario,
                o.descripcion AS descripcion,
                o.titulo AS titulo,
                o.empresa_id AS empresa_id
         FROM OFERTA o'
        );

        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        // 2. Preparar la consulta para obtener nombres de ciclos (se reutilizará)
        $queryCiclos = $conn->prepare(
            'SELECT c.nombre
             FROM OFERTA_CICLO oc
             JOIN CICLO c ON oc.ciclo_id = c.id
             WHERE oc.oferta_id = :oferta_id'
        );

        foreach ($resultados as $res) {
            
            // 3. Ejecutar la consulta de ciclos para CADA oferta
            $queryCiclos->bindValue(':oferta_id', $res['oferta_id']);
            $queryCiclos->execute();
            
            // 4. Obtener un array de nombres (Ej: ["ASIR", "DAW"])
            $ciclosNombres = $queryCiclos->fetchAll(PDO::FETCH_COLUMN);

            // 5. Crear el objeto Oferta con el array de nombres
            $ofertas[] = new Oferta(
                $res['oferta_id'],
                $res['empresa_id'],
                $ciclosNombres, // <-- Se pasa el array de nombres
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
                o.empresa_id AS empresa_id
         FROM OFERTA o
         WHERE o.id = :id'
        );

        $query->bindValue(':id', $id);
        $query->execute();

        $res = $query->fetch(PDO::FETCH_ASSOC);

        if (!$res) {
            return null;
        }

        // Obtener los nombres de los ciclos asociados
        $queryCiclos = $conn->prepare(
            'SELECT c.nombre
             FROM OFERTA_CICLO oc
             JOIN CICLO c ON oc.ciclo_id = c.id
             WHERE oc.oferta_id = :oferta_id'
        );
        $queryCiclos->bindValue(':oferta_id', $id);
        $queryCiclos->execute();
        $ciclosNombres = $queryCiclos->fetchAll(PDO::FETCH_COLUMN);

        return new Oferta(
            $res['oferta_id'],
            $res['empresa_id'],
            $ciclosNombres, // <-- Se pasa el array de nombres
            $res['fecha_creacion'],
            $res['fecha_fin'],
            $res['salario'],
            $res['descripcion'],
            $res['titulo']
        );
    }

    // UPDATE
    public static function update($oferta)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $query = $conn->prepare(
                'UPDATE OFERTA
             SET fecha_fin_oferta = :fecha_fin,
                 salario = :salario,
                 descripcion = :descripcion,
                 titulo = :titulo
             WHERE id = :id'
            );

            $query->bindValue(':fecha_fin', $oferta->fechaFin);
            $query->bindValue(':salario', $oferta->salario);
            $query->bindValue(':descripcion', $oferta->descripcion);
            $query->bindValue(':titulo', $oferta->titulo);
            $query->bindValue(':id', $oferta->id);

            $query->execute();

            // Actualizar ciclos: eliminar los viejos e insertar los nuevos
            $deleteQuery = $conn->prepare('DELETE FROM OFERTA_CICLO WHERE oferta_id = :oferta_id');
            $deleteQuery->bindValue(':oferta_id', $oferta->id);
            $deleteQuery->execute();

            if (!empty($oferta->ciclos)) {
                $insertQuery = $conn->prepare(
                    'INSERT INTO OFERTA_CICLO (oferta_id, ciclo_id) VALUES (:oferta_id, :ciclo_id)'
                );
                foreach ($oferta->ciclos as $cicloId) {
                    if (!empty($cicloId) && is_numeric($cicloId)) {
                        $insertQuery->bindValue(':oferta_id', $oferta->id);
                        $insertQuery->bindValue(':ciclo_id', $cicloId, PDO::PARAM_INT);
                        $insertQuery->execute();
                    }
                }
            }

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

            // Primero eliminar los ciclos asociados
            $queryCiclos = $conn->prepare('DELETE FROM OFERTA_CICLO WHERE oferta_id = :id');
            $queryCiclos->bindValue(':id', $id);
            $queryCiclos->execute();

            // Luego eliminar la oferta
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