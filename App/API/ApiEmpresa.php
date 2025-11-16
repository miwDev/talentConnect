<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\helper\Adapter;
use App\core\helper\Session;

$method = strtoupper($_SERVER["REQUEST_METHOD"] ?? 'GET');
$body_content = file_get_contents('php://input');
$authHeaderValue = getAuthorizationHeader(); 

switch ($method) {
    case 'GET':
        $process = $_GET['process'] ?? '';
        if($process === 'pfp'){
            getProfilePic($authHeaderValue); 
        } else if($process === 'offerProfile') {
            $empresaId = $_GET['id'];
            getProfileById($authHeaderValue, $empresaId);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
        break;
}

function getProfilePic($authHeaderValue){
    try {
        $empresaDTO = Adapter::getEmpresaDTObyToken($authHeaderValue);
        
        if($empresaDTO){
            
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'pictureRoute' => "/public" . $empresaDTO->logo,
                'username' => $empresaDTO->nombre
            ]);
            exit();
        }else{
            
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Unauthorized or Invalid Token']);
            exit();
        }
    } catch (\Exception $e) { // Usar \Exception con backslash para clase global
        error_log("ERROR en getProfilePic: " . $e->getMessage());
        
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Internal Server Error']);
        exit();
    }
}

function getProfileById($authHeaderValue, $empresaId){
    $token = Adapter::tokenRetrieve($authHeaderValue); 
    if($token !== null && $token == Session::readToken()){
        try {
            $empresaDTO = Adapter::getEmpresaDTObyId($empresaId);
            
            if($empresaDTO){
                
                header('Content-Type: application/json');
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'pictureRoute' => "/public" . $empresaDTO->logo,
                    'username' => $empresaDTO->nombre
                ]);
                exit();
            }else{
                
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Company not found']);
            }
        } catch (\Exception $e) { // Usar \Exception con backslash para clase global
            error_log("ERROR en getProfilePic: " . $e->getMessage());
            
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Internal Server Error']);
            exit();
        }
    }else{
        error_log("ERROR Authorization: UNAUTHORIZED");
            
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Unauthorized or Invalid Token']);
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