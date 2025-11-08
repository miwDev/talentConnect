<?php

namespace App\core\helper;

use App\core\data\AlumnoRepo;
use App\core\data\CicloRepo;
use App\core\data\EmpresaRepo;
use App\core\DTO\AlumnoDTO;
use App\core\DTO\EmpresaDTO;
use App\core\DTO\CicloDTO;
use App\core\model\Alumno;
use App\core\model\Empresa;
use App\core\model\Ciclo;


class Adapter
{

    static public function DTOtoAlumno($data)
    {
        $alumno = new Alumno(
            null, //id
            $data['username'], //email
            "temporalPass", //pass
            $data['nombre'], //nombre
            $data['apellido'], //apellido
            null, //dni
            $data['telefono'], //telefone
            $data['direccion'], //dir
            null, //foto
            null, //cv
            $data['provincia'], // provincia
            $data['localidad'], //localidad
            0 //confirmed = 0/false
        );

        return $alumno;
    }

    static public function AllAlumnoToDTO($alumnos)
    {
        $alumnosDTO = [];

        if (empty($alumnos)) {
            return $alumnosDTO;
        }

        foreach ($alumnos as $alumno) {
            $alumnosDTO[] = new AlumnoDTO(
                $alumno->id,
                $alumno->nombre,
                $alumno->apellido,
                $alumno->username,
                $alumno->confirmed
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
            $alumno->username,
            $alumno->confirmed
        );

        return $alumnoDTO;
    }

    static public function editedDatatoDTO($data)
    {
        $alumnoDTO = new AlumnoDTO(
            $data['id'], //id
            $data['nombre'], // nombre
            $data['apellido'], // ape
            $data['email'], // username
            0 // confirmed
        );

        return $alumnoDTO;
    }

    static public function groupDTOtoAlumno($alumnosDTO)
    {
        $fullAlumnos = [];

        foreach ($alumnosDTO as $aluDTO) {
            $alumno = new Alumno(
                null,
                $aluDTO['email'],
                "temporalPass",
                $aluDTO['nombre'],
                $aluDTO['apellido'],
                null, // dni
                900000000, // tel
                null, // direccion
                null, // foto
                null, // cv
                null, // provincia
                null,  // localidad
                0 //confirmed
            );

            $ciclo = CicloRepo::findById($aluDTO["cicloId"]);
            if ($ciclo) {
                $alumno->estudios[] = $ciclo;
            }

            $fullAlumnos[] = $alumno;
        }

        return $fullAlumnos;
    }


    static public function DTOtoEmpresa()
    {
        $empresa = new Empresa(
            null,
            $_POST['email'],
            "temporalPass",
            $_POST['cif'],
            $_POST['nombre'],
            $_POST['telefono'],
            null, //direccion
            null, //provincia
            null, //localidad
            null, //nombre persona
            null, //telPersona
            null, //logo
            0
        );

        return $empresa;
    }

    static public function AllEmpresasToDTO($empresas)
    {
        $empresasDTO = [];

        foreach ($empresas as $empresa) {
            $empresasDTO[] = new EmpresaDTO(
                $empresa->id,
                $empresa->cif,
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
            $empresa->cif,
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
        $empresaEdit->cif = $postData['cif'];
        $empresaEdit->nombre = $postData['nombre'];
        $empresaEdit->username = $postData['email'];
        $empresaEdit->telefono = $postData['telefono'];

        return EmpresaRepo::update($empresaEdit);
    }

    static public function ciclosToDTO($ciclos)
    {
        $ciclosDTO = [];
        foreach ($ciclos as $ciclo) {
            $ciclosDTO[] = new CicloDTO(
                $ciclo->id,
                $ciclo->nombre
            );
        }
        return $ciclosDTO;
    }
}
