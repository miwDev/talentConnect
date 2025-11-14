<?php

namespace App\core\data;

use App\core\model\Alumno;
use App\core\data\CicloRepo;
use App\core\model\Familia;
use App\core\data\DBC;
use PDO;
use PDOException;
use Exception;

class EstudiosRepo
{
    public static function findAllEstudios($alumnoId){
        $conn = DBC::getConnection();
        $estudios = [];

        try {
            $query = $conn->prepare(
                'SELECT ac.id AS estudio_id, 
                    ac.fecha_inicio AS fecha_ini,
                    ac.fecha_fin AS fecha_fin,
                    ac.ciclo_id AS ciclo_id,
             FROM ALUMNO_CICLO ac
             JOIN ALUMNO a ON ac.alumno_id = a.id'
            );
            $query->execute();
            $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $res) {

                $estudios[]= new Estudios(
                    $res['estudio_id'],
                    CicloRepo::findById($res['ciclo_id']),
                    Adapter::dbDatetoPHP($res['fecha_ini']),
                    Adapter::dbDatetoPHP($res['fecha_fin'])
                );
            }
        } catch (\PDOException $e) {
            echo "<pre>Error al obtener ciclos: " . $e->getMessage() . "</pre>";
        }

        return $estudios;
    }

    public static function findEstudioById($alumnoId, $cicloId){
        $conn = DBC::getConnection();

        try {
            $query = $conn->prepare(
                'SELECT ac.id AS estudio_id, 
                    ac.fecha_inicio AS fecha_ini,
                    ac.fecha_fin AS fecha_fin,
                    ac.ciclo_id AS ciclo_id,
             FROM ALUMNO_CICLO ac
             JOIN ALUMNO a ON ac.alumno_id = a.id'
            );
            $query->execute();
            $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

            $estudio = new Estudios(
                $res['estudio_id'],
                CicloRepo::findById($res['ciclo_id']),
                Adapter::dbDatetoPHP($res['fecha_ini']),
                Adapter::dbDatetoPHP($res['fecha_fin'])
            );

        } catch (\PDOException $e) {
            echo "<pre>Error al obtener ciclos: " . $e->getMessage() . "</pre>";
        }

        return $estudios;
    }

    public static function updateEstudioById($alumnoId, $cicloId){

    }
}
