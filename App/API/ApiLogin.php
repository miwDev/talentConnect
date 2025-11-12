<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\helper\Adapter;
use App\core\helper\Session;
use App\core\helper\Login;
use App\core\data\Authorization;

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
        if ($body_content == "" || empty($body_content)) {
            getToken();
        }
        break;
    case 'POST':
        $process = $_GET['process'] ?? '';
        if ($process !== "" && $process === "logout") {
            // Pasamos el string del encabezado a la función
            logoutUser($authHeaderValue);
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
        $role = Session::readRole();

        if ($token) {
            http_response_code(200);

            $response = [
                'success' => true,
                'token' => $token,
                'role' => $role,
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

function logoutUser($authHeaderValue){
    header('Content-Type: application/json');
    
    $token = Adapter::tokenRetrieve($authHeaderValue);

    if(Session::readToken() === $token){
        Authorization::deleteToken($token);
        Login::logout();
        http_response_code(204);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Token mismatch or not authorized.']);
    }
}