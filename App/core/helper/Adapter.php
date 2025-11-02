<?php

namespace App\core\helper;

use App\core\data\AlumnoRepo;
use App\core\DTO\AlumnoDTO;
use App\core\model\Alumno;

class Adapter
{

    static public function DTOtoAlumno($data)
    {
        $alumno = new Alumno( // will verify from JS that nothing is empty except the nulls
            null,
            $data['username'],
            "temporalPass", // will be changed when the user checks the confirmation email
            $data['nombre'],
            $data['apellido'],
            $data['telefono'],
            $data['direccion'],
            null, // awaiting for the user to put it
            null, // **
            $data['provincia'],
            $data['localidad']
            // confirmedUser = 0;
        );

        return $alumno;
    }

    static public function alumnoToDTO($id)
    {
        $alumno = AlumnoRepo::findById($id);

        $alumnoDTO = new AlumnoDTO(
            $alumno->id,
            $alumno->nombre,
            $alumno->apellido,
            $alumno->username
        );

        return $alumnoDTO;
    }
}
