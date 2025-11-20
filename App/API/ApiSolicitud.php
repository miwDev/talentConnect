<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\data\SolicitudRepo;
use App\core\model\Solicitud;
use App\core\data\Authorization;
use App\core\helper\Adapter;
use App\core\helper\Session;
use Exception;


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
        deleteSolicitud($body_content, $authHeaderValue);
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
        break;
}


function saveSolicitud($body, $auth){
    
    $data = json_decode($body, true);
    
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
    
    $data = json_decode($body, true);

    $solicitud = Adapter::editedDatatoSolicitud($data, $auth);

    if($solicitud){
        $dbResponse = SolicitudRepo::update($solicitud);

        if($dbResponse){
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true]);
        }else{
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false]);
        }

    }else{
        error_log("ERROR Authorization: UNAUTHORIZED");
        http_response_code(401);
        exit();
    }

}

function deleteSolicitud($body, $auth){
    $data = json_decode($body, true);
    $solicitud = Adapter::SolicitudForDeletion($data, $auth);

    if($solicitud){
        $dbResponse = SolicitudRepo::deleteById($solicitud);
        if($dbResponse){
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true]);
        }else{
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false]);
        }
    }else{
        error_log("ERROR Authorization: UNAUTHORIZED");
        http_response_code(401);
        exit();
    }
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