<?php

namespace App\core\data;

use App\core\model\Alumno;
use App\core\data\CicloRepo;
use App\core\model\Familia;
use App\core\model\Estudios;
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
                    ac.ciclo_id AS ciclo_id
             FROM ALUMNO_CICLO ac
             WHERE ac.alumno_id = :alumno_id'
            );
            $query->bindValue(':alumno_id', $alumnoId, PDO::PARAM_INT);
            $query->execute();
            $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $res) {
                $estudios[] = new Estudios(
                    $res['estudio_id'],
                    CicloRepo::findById($res['ciclo_id']),
                    $res['fecha_ini'],
                    $res['fecha_fin']
                );
            }
        } catch (\PDOException $e) {
            error_log("Error al obtener estudios: " . $e->getMessage());
        }

        return $estudios;
    }

    public static function findEstudioById($alumnoId, $cicloId){
        $conn = DBC::getConnection();
        $estudio = null;

        try {
            $query = $conn->prepare(
                'SELECT ac.id AS estudio_id, 
                    ac.fecha_inicio AS fecha_ini,
                    ac.fecha_fin AS fecha_fin,
                    ac.ciclo_id AS ciclo_id
             FROM ALUMNO_CICLO ac
             WHERE ac.alumno_id = :alumno_id AND ac.ciclo_id = :ciclo_id'
            );
            $query->bindValue(':alumno_id', $alumnoId, PDO::PARAM_INT);
            $query->bindValue(':ciclo_id', $cicloId, PDO::PARAM_INT);
            $query->execute();
            $res = $query->fetch(PDO::FETCH_ASSOC);

            if ($res) {
                $estudio = new Estudios(
                    $res['estudio_id'],
                    CicloRepo::findById($res['ciclo_id']),
                    $res['fecha_ini'],
                    $res['fecha_fin']
                );
            }

        } catch (\PDOException $e) {
            error_log("Error al obtener estudio: " . $e->getMessage());
        }

        return $estudio;
    }

    public static function saveEstudiosForAlumno($alumnoId, $estudiosArray)
    {
        if (empty($estudiosArray)) {
            error_log("⚠️ saveEstudiosForAlumno: Array de estudios vacío");
            return;
        }

        error_log("=== GUARDANDO ESTUDIOS ===");
        error_log("Alumno ID: $alumnoId");
        error_log("Cantidad de estudios: " . count($estudiosArray));

        $conn = DBC::getConnection();   
        $sql = "INSERT INTO ALUMNO_CICLO (alumno_id, ciclo_id, fecha_inicio, fecha_fin) 
                VALUES (:alumno_id, :ciclo_id, :fecha_inicio, :fecha_fin)";
        
        try {
            $stmt = $conn->prepare($sql);
            
            $contador = 0;
            foreach ($estudiosArray as $index => $estudio) {
                
                error_log("--- Guardando estudio $index ---");
                error_log("Objeto estudio: " . print_r($estudio, true));
                

                $idCiclo = $estudio->ciclo; 
                $fechaInicio = $estudio->fechaInicio;
                $fechaFin = $estudio->fechaFin;

                error_log("Ciclo ID: $idCiclo");
                error_log("Fecha Inicio: $fechaInicio");
                error_log("Fecha Fin: " . ($fechaFin ?: 'NULL'));


                $stmt->bindValue(':alumno_id', $alumnoId, PDO::PARAM_INT);
                $stmt->bindValue(':ciclo_id', $idCiclo, PDO::PARAM_INT);
                $stmt->bindValue(':fecha_inicio', $fechaInicio, PDO::PARAM_STR);
                

                if ($fechaFin === null || $fechaFin === '') {
                    $stmt->bindValue(':fecha_fin', null, PDO::PARAM_NULL);
                } else {
                    $stmt->bindValue(':fecha_fin', $fechaFin, PDO::PARAM_STR);
                }

                $resultado = $stmt->execute();
                
                if ($resultado) {
                    $contador++;
                    error_log("✅ Estudio $index guardado correctamente");
                } else {
                    error_log("❌ Error al guardar estudio $index");
                    error_log("Error info: " . print_r($stmt->errorInfo(), true));
                }
            }
            
            error_log("Total estudios guardados: $contador de " . count($estudiosArray));
            error_log("=== FIN GUARDADO ESTUDIOS ===");
            
        } catch (\PDOException $e) {
            error_log("❌ ERROR PDO en saveEstudiosForAlumno: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        } catch (\Exception $e) {
            error_log("❌ ERROR GENERAL en saveEstudiosForAlumno: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }
}