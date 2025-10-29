<?php

namespace App\core\data;

use App\core\model\Alumno;


class AlumnoRepo implements RepoInterface
{
    // CREATE

    public static function save($alumno) {}

    // READ
    public static function findAll()
    {
        $conn = DBC::getConnection();
        $alumnos = [];

        $query = $conn->prepare('SELECT id, nombre, apellido, telefono, direccion, foto, cv, provincia, localidad
                                FROM ALUMNO');
        $query->execute();

        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $alumno) {
            $alumnos[] = new Alumno(
                $alumno['id'],
                $alumno['nombre'],
                $alumno['apellido'],
                $alumno['telefono'],
                $alumno['direccion'],
                $alumno['foto'],
                $alumno['cv'],
                $alumno['provincia'],
                $alumno['localidad']
            );
        }
        return $alumnos;
    }

    public static function findById(int $id) {}

    // UPDATE
    public static function updateById($id) {}

    // DELETE
    public static function deleteById($id) {}
}
