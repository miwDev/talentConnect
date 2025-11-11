<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\data\FamiliaRepo;

$method = strtoupper($_SERVER["REQUEST_METHOD"] ?? 'GET');
$body_content = file_get_contents('php://input');

switch ($method) {
    case 'GET':
        if ($body_content == "" || empty($body_content)) {
            getFullList();
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
        echo json_encode(['success' => false]);
        break;
}

function getFullList()
{
    header('Content-Type: application/json');
    http_response_code(200);
    $familias = FamiliaRepo::findAll();
    echo json_encode($familias);
}
