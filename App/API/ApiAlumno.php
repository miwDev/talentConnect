<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\data\AlumnoRepo;
use App\core\helper\Adapter;

$method = strtoupper($_SERVER["REQUEST_METHOD"] ?? 'GET');
$body_content = file_get_contents('php://input');

switch ($method) {
    case 'GET':
        if ($body_content == "" || empty($body_content)) {
            getFullList();
        } else {
            getSizedList(); // listados paginados
        }
        break;
    case 'POST':
        $process = $_GET['process'] ?? '';
        if ($process == '') {
            saveAlumno($body_content);
        } else if ($process == "newStudent") {
            saveFullAlumno();
        }
        break;
    case 'PUT':
        $process = $_GET['process'] ?? '';
        if ($process == "edit") {
            editAlumno($body_content);
        } else if ($process == "saveAll") {
            saveGroupDTO($body_content);
        }
        break;
    case 'DELETE':
        deleteAlumno($body_content);
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false]);
        break;
}

function getFullList()
{
    header('Content-Type: application/json');
    http_response_code(200);
    $alumnos = AlumnoRepo::findAll();
    $alumnosDTO = Adapter::AllAlumnoToDTO($alumnos);
    echo json_encode($alumnosDTO);
}

function getSizedList() {}

function saveAlumno($body)
{
    $decodedBody = json_decode($body, true);
    $alumno = Adapter::DTOtoAlumno($decodedBody);
    $dbResponse = AlumnoRepo::save($alumno);

    if ($dbResponse !== false) {
        http_response_code(200);
        $alumnoDTO = Adapter::alumnoToDTO($dbResponse);
        echo json_encode([
            'success' => true,
            'alumno' => $alumnoDTO
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false]);
    }
}

function saveFullAlumno(){
    header('Content-Type: application/json');
    
    try {
        $alumno = Adapter::formDataToAlumno();
        $dbResponse = AlumnoRepo::save($alumno);

        if($dbResponse !== false){
            http_response_code(201);
            echo json_encode(['success' => true, 'alumno_id' => $dbResponse]);
        }else{
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Error al guardar en BD']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function saveGroupDTO($body)
{
    $data = json_decode($body, true);
    $resultado = AlumnoRepo::saveAll(Adapter::groupDTOtoAlumno($data));

    if (!empty($resultado['guardados']) || !empty($resultado['errores'])) {
        http_response_code(200);

        $alumnosDTO = Adapter::AllAlumnoToDTO($resultado['guardados']);

        $response = [
            'success' => true,
            'guardados' => $alumnosDTO,
            'errores' => $resultado['errores'] // Array of indices with errors
        ];

        echo json_encode($response);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false]);
    }
}

function deleteAlumno($body)
{
    $data = json_decode($body, true);
    $id = $data['id'];
    if (AlumnoRepo::deleteById($id)) {
        http_response_code(200);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false]);
    };
}

function editAlumno($body)
{
    $data = json_decode($body, true);
    if (AlumnoRepo::updateDTO(Adapter::editedDatatoDTO($data))) {
        http_response_code(200);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false]);
    }
}
