<?php

namespace App\core\helper;

use App\core\data\AlumnoRepo;
use App\core\data\CicloRepo;
use App\core\data\EmpresaRepo;
use App\core\model\OfertaRepo;
use App\core\DTO\AlumnoDTO;
use App\core\DTO\EmpresaDTO;
use App\core\DTO\CicloDTO;
use App\core\model\Alumno;
use App\core\model\Empresa;
use App\core\model\Estudios;
use App\core\model\Oferta;
use App\core\helper\Security;


    class Adapter
    {

        //////////////////////////////////////
        /// Alumno.                      /////
        /////////////////////////////////////

        public static function DTOtoAlumno($data)
        {
            $alumno = new Alumno(
                null, //id
                $data['username'], //email
                Security::passEncrypter("tempPass"), //pass
                $data['nombre'], //nombre
                $data['apellido'], //apellido
                null, //dni
                $data['telefono'], //telefone
                $data['direccion'], //dir
                '/assets/images/genericAvatar.svg', //foto
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
    $ruta_base = __DIR__ . '/../../public';
    $carpeta_destino = $ruta_base . '/assets/images/userPfp/';
    $ruta_para_base_de_datos = '/assets/images/genericAvatar.svg';

    if($fotoData && $fotoData !== 'default'){
        if (preg_match('/^data:image\/(\w+);base64,(.+)$/', $fotoData, $matches)) {
            $extension = $matches[1];
            $datos_base64 = $matches[2];

            $datos_binarios = base64_decode($datos_base64);
            $nombre_archivo = 'foto_' . uniqid() . '.' . $extension;
            $ruta_fisica_archivo = $carpeta_destino . $nombre_archivo;

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

    $alumnoFull = new Alumno(
        null,
        $_POST['email'] ?? '',
        Security::passEncrypter($_POST['password'] ?? 'securityPass'),
        $_POST['nombre'] ?? '',
        $_POST['apellido'] ?? '',
        $_POST['dni'] ?? '',
        $_POST['telefono'] ?? '',
        $_POST['direccion'] ?? '',
        $ruta_para_base_de_datos,
        $ruta_cv_db,
        $_POST['provincia'] ?? '',
        $_POST['localidad'] ?? '',
        1
    );

    // ✅ PROCESAMIENTO DE ESTUDIOS MEJORADO CON LOGS
    $listaDeEstudios = [];

    error_log("=== INICIO PROCESAMIENTO ESTUDIOS ===");
    error_log("POST familia: " . print_r($_POST['familia'] ?? 'NO EXISTE', true));
    error_log("POST ciclo: " . print_r($_POST['ciclo'] ?? 'NO EXISTE', true));
    error_log("POST fecha_inicio: " . print_r($_POST['fecha_inicio'] ?? 'NO EXISTE', true));
    error_log("POST fecha_fin: " . print_r($_POST['fecha_fin'] ?? 'NO EXISTE', true));

    if (isset($_POST['familia']) && is_array($_POST['familia'])) {

        foreach ($_POST['familia'] as $key => $idFamilia) {

            $idCiclo = $_POST['ciclo'][$key] ?? null;
            $fechaInicio = $_POST['fecha_inicio'][$key] ?? null;
            $fechaFin = $_POST['fecha_fin'][$key] ?? null;

            error_log("--- Iteración $key ---");
            error_log("idFamilia: $idFamilia");
            error_log("idCiclo: $idCiclo");
            error_log("fechaInicio: $fechaInicio");
            error_log("fechaFin: " . ($fechaFin ?: 'NULL'));

            // ✅ CONDICIÓN SIMPLIFICADA Y CLARA
            $cicloValido = !empty($idCiclo) && $idCiclo !== "-1";
            $fechaInicioValida = !empty($fechaInicio);
            
            if ($cicloValido && $fechaInicioValida) {
                
                $fechaFinFinal = (!empty($fechaFin)) ? $fechaFin : null;
                
                $nuevoEstudio = new Estudios(null, $idCiclo, $fechaInicio, $fechaFinFinal);
                $listaDeEstudios[] = $nuevoEstudio;
                
                error_log("✅ Estudio agregado: Ciclo $idCiclo, Inicio: $fechaInicio, Fin: " . ($fechaFinFinal ?: 'NULL'));
            } else {
                error_log("❌ Estudio NO agregado - Ciclo válido: " . ($cicloValido ? 'SI' : 'NO') . ", Fecha válida: " . ($fechaInicioValida ? 'SI' : 'NO'));
            }
        }
    } else {
        error_log("❌ No se encontró el array de familias en POST");
    }

    error_log("Total de estudios procesados: " . count($listaDeEstudios));
    error_log("=== FIN PROCESAMIENTO ESTUDIOS ===");

    $alumnoFull->estudios = $listaDeEstudios;

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
                $alumno->foto,
                $alumno->confirmed
            );
        }

        return $alumnosDTO;
    }

    public static function alumnoToDTO($id)
    {
        $alumno = AlumnoRepo::findById($id);

        $alumnoDTO = new AlumnoDTO(
            $alumno->id,
            $alumno->nombre,
            $alumno->apellido,
            $alumno->username,
            $alumno->foto,
            $alumno->confirmed
        );

        return $alumnoDTO;
    }

    public static function editedDatatoDTO($data)
    {
        $alumnoDTO = new AlumnoDTO(
            $data['id'], //id
            $data['nombre'], // nombre
            $data['apellido'], // ape
            $data['email'], // username
            null,   //foto
            0 // confirmed
        );

        return $alumnoDTO;
    }

    public static function groupDTOtoAlumno($alumnosDTO)
    {
        $fullAlumnos = [];

        foreach ($alumnosDTO as $aluDTO) {
            $alumno = new Alumno(
                null,
                $aluDTO['email'],
                Security::passEncrypter("tempPass"),
                $aluDTO['nombre'],
                $aluDTO['apellido'],
                null, // dni
                900000000, // tel
                null, // direccion
                '/assets/images/genericAvatar.svg', // foto
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

    public static function getAlumnoDTObyToken($authHeaderValue){

        $token = self::tokenRetrieve($authHeaderValue); 

        if (is_null($token)) {
            $alumnoDTO = false;
        }

        $alumno = AlumnoRepo::findByToken($token);

        if($alumno){
            
            $alumnoDTO = new AlumnoDTO(
                $alumno->id,
                $alumno->nombre,
                $alumno->apellido,
                $alumno->username,
                $alumno->foto,
                $alumno->confirmed
            );
        }else{
            $alumnoDTO = false;
        }
        return $alumnoDTO;
    }


    //////////////////////////////////////
    /// EMPRESA                     /////
    /////////////////////////////////////


    public static function DTOtoEmpresa()
    {
        $empresa = new Empresa(
            null,
            $_POST['email'],
            Security::passEncrypter("tempPass"),
            $_POST['cif'],
            $_POST['nombre'],
            $_POST['telefono'],
            null, //direccion
            null, //provincia
            null, //localidad
            null, //nombre persona
            null, //telPersona
            '/assets/images/companyNull.svg', //logo
            0
        );

        return $empresa;
    }

    public static function AllEmpresasToDTO($empresas)
    {
        $empresasDTO = [];

        foreach ($empresas as $empresa) {
            $empresasDTO[] = new EmpresaDTO(
                $empresa->id,
                $empresa->cif,
                $empresa->nombre,
                $empresa->username,
                $empresa->telefono,
                $empresa->logo,
                $empresa->validacion
            );
        }
        return $empresasDTO;
    }

    public static function empresaToDTO($id)
    {
        $empresa = EmpresaRepo::findById($id);

        $empresaDTO = new EmpresaDTO(
            $empresa->id,
            $empresa->cif,
            $empresa->nombre,
            $empresa->username,
            $empresa->telefono,
            $empresa->logo,
            $empresa->validacion,
        );

        return $empresaDTO;
    }

    public static function empresaEditDTO($id, $postData)
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

    public static function postDatatoEmpresa(){

        $fotoData = $_POST['fotoData'] ?? null;
        $ruta_base = __DIR__ . '/../../public';
        $carpeta_destino = $ruta_base . '/assets/images/companyPfp/';
        $ruta_para_base_de_datos = '/assets/images/companyNull.svg'; // Default

        if($fotoData && $fotoData !== 'default'){
            if (preg_match('/^data:image\/(\w+);base64,(.+)$/', $fotoData, $matches)) {
                $extension = $matches[1];
                $datos_base64 = $matches[2];

                $datos_binarios = base64_decode($datos_base64);
                $nombre_archivo = 'foto_' . uniqid() . '.' . $extension;
                $ruta_fisica_archivo = $carpeta_destino . $nombre_archivo;

                if (file_put_contents($ruta_fisica_archivo, $datos_binarios)) {
                    $ruta_para_base_de_datos = '/assets/images/companyPfp/' . $nombre_archivo; 
                } else {
                    error_log("No se pudo guardar la imagen: " . $ruta_fisica_archivo);
                }
            }
        }

        $empresaFull = new Empresa(
            null,
            $_POST["email"],
            Security::passEncrypter($_POST["password"]),
            $_POST["cif"],
            $_POST["nombre_empresa"],
            $_POST["telefono"],
            $_POST["direccion"],
            $_POST["provincia"],
            $_POST["localidad"],
            $_POST["contacto_persona"],
            $_POST["contacto_telefono"],
            $ruta_para_base_de_datos,
            0 // verificacion siempre pendiente del admin
        );

        return $empresaFull;
    }

    public static function getEmpresaDTObyToken($authHeaderValue){

        $token = self::tokenRetrieve($authHeaderValue); 

        if (is_null($token)) {
            $EmpresaDTO = false;
        }

        $empresa = EmpresaRepo::findByToken($token);

        if($empresa){
            
            $empresaDTO = new EmpresaDTO(
                $empresa->id,
                $empresa->cif,
                $empresa->nombre,
                $empresa->username,
                $empresa->telefono,
                $empresa->logo,
                $empresa->validacion
            );
        }else{
            $empresaDTO = false;
        }
        return $empresaDTO;
    }

    public static function getEmpresaDTObyId($id){

        $empresa = EmpresaRepo::findById($id);

        if(!$empresa){
            $empresaDTO = false;
        }
        else if($empresa){
            
            $empresaDTO = new EmpresaDTO(
                $empresa->id,
                $empresa->cif,
                $empresa->nombre,
                $empresa->username,
                $empresa->telefono,
                $empresa->logo,
                $empresa->validacion
            );
        }else{
            $empresaDTO = false;
        }
        return $empresaDTO;
    }

     //////////////////////////////////////
    /// CICLOS                       /////
    /////////////////////////////////////

    public static function ciclosToDTO($ciclos)
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




    //////////////////////////////////////
    /// OFERTAS                      /////
    /////////////////////////////////////

    public static function postDataToOffer()
    { 
        $ciclos = [];
        
        if (!empty($_POST['select1']) && $_POST['select1'] !== '' && $_POST['select1'] !== '-1') {
            $ciclos[] = (int)$_POST['select1'];
        }
        
        if (!empty($_POST['select2']) && $_POST['select2'] !== '' && $_POST['select2'] !== '-1') {
            $ciclos[] = (int)$_POST['select2']; // Convertir a entero
        }

        // Obtener el ID de la empresa del usuario actual
        $empresaId = Session::readUserId();

        // Crear la oferta con los datos del formulario
        $oferta = new Oferta(
            null,                    // id (se generará automáticamente)
            (int)$empresaId,        // empresaId (convertir a entero)
            $ciclos,                 // array de ciclo IDs
            null,                    // fechaCreacion (se asigna por defecto en la BD)
            $_POST["fechaFin"],      // fechaFin
            $_POST['salario'],       // salario
            $_POST['desc'],          // descripcion
            $_POST['titulo']         // titulo
        );

        return $oferta;
    }

    public static function editedDataToOferta()
{
    $ciclos = [];
    
    // Asegurarse de que los valores se convierten a enteros y se validan
    if (!empty($_POST['select1']) && $_POST['select1'] !== '' && $_POST['select1'] !== '-1') {
        $ciclos[] = (int)$_POST['select1'];
    }
    
    if (!empty($_POST['select2']) && $_POST['select2'] !== '' && $_POST['select2'] !== '-1') {
        $ciclos[] = (int)$_POST['select2'];
    }

    // Log para debug
    error_log("=== DEBUG EDICION OFERTA ===");
    error_log("POST select1: " . ($_POST['select1'] ?? 'NO EXISTE'));
    error_log("POST select2: " . ($_POST['select2'] ?? 'NO EXISTE'));
    error_log("Ciclos procesados: " . print_r($ciclos, true));
    error_log("Total ciclos: " . count($ciclos));
    error_log("=========================");

    $empresaId = Session::readUserId();

    $oferta = new Oferta(
        (int)$_POST['id'],           // Asegurar que el ID es entero
        (int)$empresaId,             // empresaId
        $ciclos,                      // array de ciclo IDs
        null,                         // fechaCreacion
        $_POST["fechaFin"],          // fechaFin
        $_POST['salario'],           // salario
        $_POST['descripcion'],       // descripcion
        $_POST['titulo']             // titulo
    );

    return $oferta;
}


    


    //////////////////////////////////////
    /// TOKEN                        /////
    /////////////////////////////////////

     public static function tokenRetrieve($authHeaderValue){ 
        
        

        if (empty($authHeaderValue)) {
            $token = null;
        }

        $token = trim(preg_replace('/^Bearer:?\s*/i', '', $authHeaderValue));

        return $token;
    }
}