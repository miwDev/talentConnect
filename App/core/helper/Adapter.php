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


class Adapter
{

    public static function DTOtoAlumno($data)
    {
        $alumno = new Alumno(
            null, //id
            $data['username'], //email
            "tempPass", //pass
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

    public static function formDataToAlumno(){

        // Lógica de guardado de la foto de perfil
        $fotoData = $_POST['fotoPerfil'] ?? null;
        $ruta_base = __DIR__ . '/../../public'; // Corregido: desde App/core/helper a App/public
        $carpeta_destino = $ruta_base . '/assets/images/userPfp/';
        $ruta_para_base_de_datos = '/assets/images/genericAvatar.svg'; // Default

        if($fotoData && $fotoData !== 'default'){
            if (preg_match('/^data:image\/(\w+);base64,(.+)$/', $fotoData, $matches)) {
                $extension = $matches[1];
                $datos_base64 = $matches[2];

                $datos_binarios = base64_decode($datos_base64);
                $nombre_archivo = 'foto_' . uniqid() . '.' . $extension;
                $ruta_fisica_archivo = $carpeta_destino . $nombre_archivo;

                // Crear directorio si no existe
                if (!is_dir($carpeta_destino)) {
                    mkdir($carpeta_destino, 0755, true);
                }

                if (file_put_contents($ruta_fisica_archivo, $datos_binarios)) {
                    $ruta_para_base_de_datos = '/assets/images/userPfp/' . $nombre_archivo; 
                } else {
                    error_log("No se pudo guardar la imagen: " . $ruta_fisica_archivo);
                }
            }
        }

        // Lógica de guardado del CV
        $carpeta_destino_cv = $ruta_base . '/assets/uploads/cvs/';
        $ruta_cv_db = null;

        if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
            $archivo_temporal = $_FILES['cv']['tmp_name'];
            $nombre_original = $_FILES['cv']['name'];
            $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
            
            $dni_alumno = $_POST['dni'] ?? 'sin_dni'; 
            $nombre_seguro = preg_replace('/[^a-zA-Z0-9]/', '_', $dni_alumno) . '.' . $extension;
            
            // Crear directorio si no existe
            if (!is_dir($carpeta_destino_cv)) {
                mkdir($carpeta_destino_cv, 0755, true);
            }
            
            $ruta_destino_final = $carpeta_destino_cv . $nombre_seguro;
            
            if (move_uploaded_file($archivo_temporal, $ruta_destino_final)) {
                $ruta_cv_db = '/assets/uploads/cvs/' . $nombre_seguro;
            } else {
                error_log("Error al mover el archivo CV a: " . $ruta_destino_final);
            }
        } else {
            error_log("No se recibió el archivo CV o hubo un error de subida: " . ($_FILES['cv']['error'] ?? 'No set'));
        }

        // Crear objeto Alumno con el orden correcto de parámetros
        // Constructor: ($id, $username, $pass, $nom, $ape, $dni, $tel, $direccion, $foto, $cv, $prov, $loc, $conf)
        $alumnoFull = new Alumno(
            null,                           // id
            $_POST['email'] ?? '',          // username
            $_POST['password'] ?? '',       // password
            $_POST['nombre'] ?? '',         // nombre
            $_POST['apellido'] ?? '',       // apellido
            $_POST['dni'] ?? '',            // dni
            $_POST['telefono'] ?? '',       // telefono
            $_POST['direccion'] ?? '',      // direccion
            $ruta_para_base_de_datos,       // foto
            $ruta_cv_db,                    // cv
            $_POST['provincia'] ?? '',      // provincia
            $_POST['localidad'] ?? '',      // localidad
            1                               // confirmed
        );

        return $alumnoFull;
    }

    public static function AllAlumnoToDTO($alumnos)
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
                "tempPass",
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
            "tempPass",
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
