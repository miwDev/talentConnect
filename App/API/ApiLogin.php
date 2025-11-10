<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\data\AlumnoRepo;
use App\core\helper\Adapter;
use App\ccore\data\Authorization;

$method = strtoupper($_SERVER["REQUEST_METHOD"] ?? 'GET');
$body_content = file_get_contents('php://input');

switch ($method) {
    case 'GET':
        if ($body_content == "" || empty($body_content)) {
            obtenerToken();
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false]);
        break;
}

function getToken()
{
    $token = Session::readToken();

    if ($token) {
        http_response_code(200);

        $response = [
            'success' => true,
            'token' => $token,
        ];

        echo json_encode($response);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false]);
    }


    echo json_encode($response);
}
