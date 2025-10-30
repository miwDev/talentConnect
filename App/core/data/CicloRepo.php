<?php

namespace App\core\data;

use App\core\model\Ciclo;

use PDO;
use PDOException;
use Exception;

class CicloRepo implements RepoInterface
{
    // CREATE
    public static function save($ciclo)
    {
        $conn = DBC::getConnection();
        $cicloId = false;

        try {
            $conn->beginTransaction();

            $queryciclo = 'INSERT INTO CICLO
                           (nombre, familia_id, nivel)
                           VALUES (:nom, :familia, :nivel)';
            $stmtCiclo = $conn->prepare($queryciclo);
            $stmtCiclo->bindValue(':nom', $ciclo->nombre);
            $stmtCiclo->bindValue(':familia', $ciclo->familia->id);
            $stmtCiclo->bindValue(':nivel', $ciclo->nivel);

            $stmtCiclo->execute();

            $cicloId = $conn->lastInsertId();

            $conn->commit();
        } catch (\PDOException $e) {
            $conn->rollBack();
            $cicloId = false;
        }

        return $cicloId;
    }

    // READ
    public static function findAll()
    {
        $conn = DBC::getConnection();
        $ciclos = [];

        try {
            $query = $conn->prepare(
                'SELECT c.id AS ciclo_id, 
                    c.nombre AS ciclo_nombre,
                    c.nivel AS ciclo_nivel,
                    f.id AS familia_id,
                    f.nombre AS familia_nom 
             FROM CICLO c
             JOIN FAMILIA f ON c.familia_id = f.id'
            );
            $query->execute();
            $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $res) {
                $familia = new Familia($res['familia_id'], $res['familia_nom']);
                $ciclos[] = new Ciclo($res['ciclo_id'], $res['ciclo_nombre'], $res['ciclo_nivel'], $familia);
            }
        } catch (\PDOException $e) {
            echo "<pre>Error al obtener ciclos: " . $e->getMessage() . "</pre>";
        }

        return $ciclos;
    }

    public static function findById(int $id)
    {
        $conn = DBC::getConnection();
        $ciclo = null;

        try {
            $stmt = $conn->prepare('SELECT c.id AS ciclo_id, 
                                            c.nombre AS ciclo_nombre,
                                            c.nivel AS ciclo_nivel,
                                            f.id AS familia_id,
                                            f.nombre AS familia_nom 
                                    FROM CICLO c
                                    JOIN FAMILIA f
                                        ON c.familia_id = f.id  
                                    WHERE id = :id');
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res) {
                $familia = new Familia($res['familia_id'], $res['familia_nom']);
                $ciclo = new Ciclo(res["ciclo_id"], res["ciclo_nombre"], res["ciclo_nivel"], $familia);
            }
        } catch (\PDOException $e) {
        }

        return $ciclo;
    }

    // UPDATE
    public static function updateById($ciclo)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $conn->beginTransaction();

            $query = $conn->prepare(
                'UPDATE CICLO
             SET nombre = :nombre,
                 familia_id = :familia_id,
                 nivel = :nivel
             WHERE id = :id'
            );
            $query->bindValue(':nombre', $ciclo->nombre);
            $query->bindValue(':familia_id', $ciclo->familia->id);
            $query->bindValue(':nivel', $ciclo->nivel);
            $query->bindValue(':id', $ciclo->id);

            $query->execute();
            $conn->commit();
            $salida = true;
        } catch (\PDOException $e) {
            $conn->rollBack();
            echo "<pre>Error al actualizar ciclo: " . $e->getMessage() . "</pre>";
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

            $stmt = $conn->prepare('DELETE FROM CICLO WHERE id = :id');
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $conn->commit();
            $salida = true;
        } catch (\PDOException $e) {
            $conn->rollBack();
            echo "<pre>Error al borrar ciclo: " . $e->getMessage() . "</pre>";
            $salida = false;
        }

        return $salida;
    }
}
