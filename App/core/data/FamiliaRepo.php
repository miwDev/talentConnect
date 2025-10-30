<?php

namespace App\core\data;

use PDO;
use PDOException;
use Exception;

use App\core\model\Familia;

class FamiliaRepo implements RepoInterface
{
    // CREATE
    public static function save($familia)
    {
        $conn = DBC::getConnection();
        $familiaId = false;

        try {
            $conn->beginTransaction();

            $queryfamilia = 'INSERT INTO FAMILIA
                           (nombre)
                           VALUES (:nom)';
            $stmtFamilia = $conn->prepare($queryfamilia);
            $stmtFamilia->bindValue(':nom', $familia->nombre);
            $stmtFamilia->execute();

            $familiaId = $conn->lastInsertId();

            $conn->commit();
        } catch (\PDOException $e) {
            $conn->rollBack();
            echo "<pre>Error al insertar familia: " . $e->getMessage() . "</pre>";
            $familiaId = false;
        }

        return $familiaId;
    }

    // READ
    public static function findAll()
    {
        $conn = DBC::getConnection();
        $familias = [];

        try {
            $query = $conn->prepare('SELECT id, nombre FROM FAMILIA');
            $query->execute();
            $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $res) {
                $familias[] = new Familia(
                    $res["id"],
                    $res["nombre"]
                );
            }
        } catch (\PDOException $e) {
            echo "<pre>Error al obtener familias: " . $e->getMessage() . "</pre>";
        }

        return $familias;
    }

    // FIND BY ID
    public static function findById($id)
    {
        $conn = DBC::getConnection();
        $familia = null;

        try {
            $stmt = $conn->prepare('SELECT id, nombre FROM FAMILIA WHERE id = :id');
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res) {
                $familia = new Familia($res['id'], $res['nombre']);
            }
        } catch (\PDOException $e) {
            echo "<pre>Error al obtener familia por ID: " . $e->getMessage() . "</pre>";
        }

        return $familia;
    }

    // UPDATE
    public static function updateById($familia)
    {
        $conn = DBC::getConnection();
        $salida = false;

        try {
            $stmt = $conn->prepare('UPDATE FAMILIA SET nombre = :nombre WHERE id = :id');
            $stmt->bindValue(':nombre', $familia->nombre);
            $stmt->bindValue(':id', $familia->id);
            $stmt->execute();
            $salida = true;
        } catch (\PDOException $e) {
            echo "<pre>Error al actualizar familia: " . $e->getMessage() . "</pre>";
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
            $stmt = $conn->prepare('DELETE FROM FAMILIA WHERE id = :id');
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $salida = true;
        } catch (\PDOException $e) {
            echo "<pre>Error al eliminar familia: " . $e->getMessage() . "</pre>";
            $salida = false;
        }

        return $salida;
    }
}
