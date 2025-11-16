<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\data\SolicitudRepo;
use App\core\model\Solicitud;
use App\core\data\Authorization;
use App\core\helper\Adapter;
use App\core\helper\Session;
use Exception;

// ✅ IMPORTANTE: NO mostrar errores, solo loguearlos
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$method = strtoupper($_SERVER["REQUEST_METHOD"] ?? 'GET');
$body_content = file_get_contents('php://input');

$authHeaderValue = getAuthorizationHeader(); 


switch ($method) {
    case 'GET':
        break;
    case 'POST':
        saveSolicitud($body_content, $authHeaderValue);
        break;
    case 'PUT':
        updateSolicitud($body_content, $authHeaderValue);
        break;
    case 'DELETE':
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
        break;
}


function saveSolicitud($body, $auth){
    
    $data = json_decode($body, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("ERROR JSON: " . json_last_error_msg());
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid JSON: ' . json_last_error_msg()]);
        exit();
    }
    
    $solicitud = Adapter::DTOtoSolicitud($data, $auth);
    
    if($solicitud){
        $dbResponse = SolicitudRepo::save($solicitud);
        
        if($dbResponse){
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'id' => $dbResponse]);
        }else{
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error al guardar la solicitud']);
        }
    }else{
        error_log("ERROR Authorization: UNAUTHORIZED");
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized or Invalid Token']);
    }
    
    exit();
}

function updateSolicitud($body, $auth){
}

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