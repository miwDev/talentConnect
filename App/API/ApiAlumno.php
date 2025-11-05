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
        saveAlumno($body_content);
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

function saveGroupDTO($body)
{
    $data = json_decode($body, true);
    $alumnos = AlumnoRepo::saveAll(Adapter::groupDTOtoAlumno($data));

    if (!empty($alumnos)) {
        http_response_code(200);
        $alumnosDTO = Adapter::AllAlumnoToDTO($alumnos);
        echo json_encode($alumnosDTO);
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
    if (AlumnoRepo::updateDTO($data)) {
        http_response_code(200);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false]);
    }
}
