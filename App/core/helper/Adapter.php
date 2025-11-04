<?php

namespace App\core\helper;

use App\core\data\AlumnoRepo;
use App\core\data\EmpresaRepo;
use App\core\DTO\AlumnoDTO;
use App\core\DTO\EmpresaDTO;
use App\core\model\Alumno;

class Adapter
{

    static public function DTOtoAlumno($data)
    {
        $alumno = new Alumno(
            null,
            $data['username'],
            "temporalPass",
            $data['nombre'],
            $data['apellido'],
            $data['telefono'],
            $data['direccion'],
            null,
            null,
            $data['provincia'],
            $data['localidad']
        );

        return $alumno;
    }

    static public function AllAlumnoToDTO($alumnos)
    {
        $alumnosDTO = [];

        foreach ($alumnos as $alumno) {
            $alumnosDTO[] = new AlumnoDTO(
                $alumno->id,
                $alumno->nombre,
                $alumno->apellido,
                $alumno->username
            );
        }

        return $alumnosDTO;
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

    static public function AllEmpresasToDTO($empresas)
    {
        $empresasDTO = [];

        foreach ($empresas as $empresa) {
            $empresasDTO[] = new EmpresaDTO(
                $empresa->id,
                $empresa->nombre,
                $empresa->username,
                $empresa->telefono,
                $empresa->validacion
            );
        }
        return $empresasDTO;
    }

    static public function empresaToDTO($id)
    {
        $empresa = EmpresaRepo::findById($id);

        $empresaDTO = new EmpresaDTO(
            $empresa->id,
            $empresa->nombre,
            $empresa->username,
            $empresa->telefono,
            $empresa->validacion,
        );

        return $empresaDTO;
    }

    static public function empresaEditDTO($id, $postData)
    {
        $empresaEdit = EmpresaRepo::findById($id);

        if (!$empresaEdit) {
            return false;
        }

        // Update only the fields that are in the form
        $empresaEdit->nombre = $postData['nombre'];
        $empresaEdit->username = $postData['email'];
        $empresaEdit->telefono = $postData['telefono'];

        return EmpresaRepo::update($empresaEdit);
    }
}
