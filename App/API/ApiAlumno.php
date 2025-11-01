<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\data\AlumnoRepo;
use App\core\model\Alumno;
use App\helper\Adapter;

$method = strtoupper($_SERVER["REQUEST_METHOD"] ?? 'GET');
$body_content = file_get_contents('php://input');

switch ($method) {
    case 'GET':
        if ($body_content == "" || empty($body_content)) {
            getFullList();
        } else {
            getSizedList();
        }
        break;
    case 'POST':
        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Error de Metodo']);
        break;
}

function getFullList()
{
    header('Content-Type: application/json');
    http_response_code(200);
    $alumnos = AlumnoRepo::findAllDTO();
    echo json_encode($alumnos);
}

function getSizedList() {}
