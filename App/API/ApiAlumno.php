<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\data\AlumnoRepo;
use App\core\data\Authorization;
use App\core\helper\Adapter;
use App\core\helper\Session;
use App\mail\Mailer;
use Exception;

// Función para obtener headers de forma robusta (compatible con getallheaders() y $_SERVER)
function getAuthorizationHeader() {
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        return $headers['Authorization'] ?? null;
    }
    
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return $_SERVER['HTTP_AUTHORIZATION'];
    }

    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    }

    return null;
}

$method = strtoupper($_SERVER["REQUEST_METHOD"] ?? 'GET');
$body_content = file_get_contents('php://input');

$authHeaderValue = getAuthorizationHeader(); 


switch ($method) {
    case 'GET':
        $process = $_GET['process'] ?? '';
        if (($body_content == "" || empty($body_content)) && $process === '' ) {
            getFullList($authHeaderValue);
        } else if($process === 'pfp'){
            getProfilePic($authHeaderValue); 
        }
        break;
    case 'POST':
        $process = $_GET['process'] ?? '';
        if ($process == '') {
            saveAlumno($authHeaderValue);
        }else if ($process == "newStudent") {
            saveFullAlumno($authHeaderValue);
        }else if ($process == "saveAll") {
            saveGroupDTO($body_content, $authHeaderValue);
        }
        break;
    case 'PUT':
        $process = $_GET['process'] ?? '';
        if ($process == "edit") {
            editAlumno($body_content, $authHeaderValue);
        }
        break;
    case 'DELETE':
        deleteAlumno($body_content, $authHeaderValue);
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
        break;
}

function getFullList($authHeaderValue)
{
    $token = Adapter::tokenRetrieve($authHeaderValue); 
    $role = Authorization::getRoleByToken($token);

    if ($role === 'ROLE_ADMIN') {
        header('Content-Type: application/json');
        http_response_code(200);
        $alumnos = AlumnoRepo::findAll();
        $alumnosDTO = Adapter::AllAlumnoToDTO($alumnos);
        echo json_encode($alumnosDTO);
    }else{
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized or Invalid Token']);
    }
}

function getProfilePic($authHeaderValue){
    $alumnoDTO = Adapter::getAlumnoDTObyToken($authHeaderValue);

    if($alumnoDTO){
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
                        'success' => true,
                        'pictureRoute' => "/public" . $alumnoDTO->foto,
                        'username' => $alumnoDTO->email
                        ]);
    }else{
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized or Invalid Token']);
    }
}

function saveAlumno($authHeaderValue) // el form data va por $_POST
{
    $token = Adapter::tokenRetrieve($authHeaderValue); 
    $role = Authorization::getRoleByToken($token);

    if ($role === 'ROLE_ADMIN') {
        $alumno = Adapter::DTOtoAlumno();
        $dbResponse = AlumnoRepo::save($alumno);

        if ($dbResponse !== false) {
            header('Content-Type: application/json');
            http_response_code(201);
            $alumnoDTO = Adapter::alumnoToDTO($dbResponse);
            echo json_encode([
                'success' => true,
                'alumno' => $alumnoDTO
            ]);
        }else{
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Error saving Alumno']);
        }
    }
    else{
        header('Content-Type: application/json');
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Unauthorized or Invalid Token']);
    }
}

function saveFullAlumno(){
    header('Content-Type: application/json');
    
    try {
        $alumno = Adapter::formDataToAlumno();
        $alumnoId = AlumnoRepo::save($alumno);

        if($alumnoId !== false){

            $authData = Authorization::registeredAlumno($alumnoId);

            if($authData){
                Session::setSession($authData);
            }
            http_response_code(201);
            echo json_encode([
                'success' => true, 
                'redirect' => '?menu=alumno-dashboard'
            ]);
        }else{
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Error al guardar en BD']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function saveGroupDTO($body, $authHeaderValue)
{

    $token = Adapter::tokenRetrieve($authHeaderValue); 
    $role = Authorization::getRoleByToken($token);

    if ($role === 'ROLE_ADMIN') {
        $data = json_decode($body, true);
        $resultado = AlumnoRepo::saveAll(Adapter::groupDTOtoAlumno($data));

        if (!empty($resultado['guardados']) || !empty($resultado['errores'])) {
            $mailer = new Mailer();
            foreach ($resultado['guardados'] as $alumno) {
                $emailAlu = $alumno->username; 
                try {
                    $mailer->accountConfirmAlumno($emailAlu, $alumno->id);
                } catch (Exception $e) {
                    error_log("Error enviando mail a $emailAlu: " . $e->getMessage());
                }
            }

            header('Content-Type: application/json');
            http_response_code(200);

            $alumnosDTO = Adapter::AllAlumnoToDTO($resultado['guardados']);

            $response = [
                'success' => true,
                'guardados' => $alumnosDTO,
                'errores' => $resultado['errores']
            ];

            echo json_encode($response);
        } else {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'No data saved or found']);
        }
    }else{
        header('Content-Type: application/json');
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Unauthorized or Invalid Token']);
    }
}

function deleteAlumno($body, $authHeaderValue)
{
    $token = Adapter::tokenRetrieve($authHeaderValue); 
    $role = Authorization::getRoleByToken($token);

    if ($role === 'ROLE_ADMIN') { // role ALU para que un alumno pueda borrar su cuenta
        $data = json_decode($body, true);
        $id = $data['id'];

        $usuario = AlumnoRepo::findById($id);

        if (!$usuario) {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Student not found']);
            return;
        }

        $rutaFoto = $usuario->foto;
        $rutaCv = $usuario->cv;
        $basePath = __DIR__ . '/../public';

        if ($rutaFoto && $rutaFoto !== '/assets/images/genericAvatar.svg') {
            $fullFotoPath = $basePath . $rutaFoto;
            
            if (file_exists($fullFotoPath)) {
                unlink($fullFotoPath);
            }
        }

        if ($rutaCv) {
            $fullCvPath = $basePath . $rutaCv;
            
            if (file_exists($fullCvPath)) {
                unlink($fullCvPath);
            }
        }
        
        if (AlumnoRepo::deleteById($id)) {
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error deleting student from database']);
        }
    }else{
        header('Content-Type: application/json');
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Unauthorized or Invalid Token']);
    }
}

function editAlumno($body, $authHeaderValue)
{
    $token = Adapter::tokenRetrieve($authHeaderValue); 
    $role = Authorization::getRoleByToken($token);

    if ($role === 'ROLE_ADMIN') {
        $data = json_decode($body, true);
        if (AlumnoRepo::updateDTO(Adapter::editedDatatoDTO($data))) {
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Could not update student data']);
        }
    }else{
        header('Content-Type: application/json');
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Unauthorized or Invalid Token']);
    }
}