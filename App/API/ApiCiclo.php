<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\data\CicloRepo;
use App\core\helper\Adapter;

// Configura el encabezado predeterminado para JSON
header('Content-Type: application/json');

$method = strtoupper($_SERVER["REQUEST_METHOD"] ?? 'GET');
// $body_content no es necesario para GET, lo mantengo si lo usas en POST/PUT

switch ($method) {
    case 'GET':
        if (!isset($_GET['familia']) || empty($_GET['familia'])) {
            getFullList();
        } else {
            getListByFamily($_GET['familia']);
        }
        break;
    case 'POST':
        http_response_code(501); // Not Implemented
        echo json_encode(['success' => false, 'message' => 'POST method not yet supported.']);
        break;
    case 'PUT':
        http_response_code(501); // Not Implemented
        echo json_encode(['success' => false, 'message' => 'POST method not yet supported.']);
        break;
    case 'DELETE':
        http_response_code(501); // Not Implemented
        echo json_encode(['success' => false, 'message' => 'DEL Method not yet supported.']);
        break;
    default:
        // Método no permitido
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
        break;
}

function getFullList()
{
    http_response_code(200);
    try {
        $ciclos = CicloRepo::findAll();
        echo json_encode($ciclos);
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error fetching full list.']);
    }
}

function getListByFamily($familia)
{
    $id = $familia;

    try {
        $ciclos = Adapter::ciclosToDTO(CicloRepo::findCiclosByFamily($id));
        http_response_code(200);
        echo json_encode($ciclos);
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error fetching list by family.']);
    }
}
