<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\helper\Session;
use App\core\helper\Login;

$method = strtoupper($_SERVER["REQUEST_METHOD"] ?? 'GET');
$body_content = file_get_contents('php://input');

switch ($method) {
    case 'GET':
        if ($body_content == "" || empty($body_content)) {
            getToken();
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false]);
        break;
}

function getToken()
{
    if (Login::loggedIn()) {
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
            echo json_encode(['success' => false, 'token' => ""]);
        }
    } else {
        http_response_code(403);
        echo json_encode(['success' => false]);
    }
}
