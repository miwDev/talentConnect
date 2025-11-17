<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helper/Autoloader.php';

use App\core\helper\Adapter;
use App\core\data\Authorization;
use App\core\data\OfertaRepo;
use App\core\data\EmpresaRepo;
use PDOException;
use Exception;

// --- Funciones Auxiliares ---

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
$empresaDTO = Adapter::getEmpresaDTObyToken($authHeaderValue);
$empresaId = $empresaDTO ? $empresaDTO->id : 0;

header('Content-Type: application/json');

if (!is_numeric($empresaId) || $empresaId <= 0) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Acceso denegado o empresa no identificada.']);
    exit;
}


switch ($method) {
    case 'GET':
        $stat = $_GET['statType'] ?? '';
        
        try {
            switch ($stat) {
                
                // --- Indicadores Clave ---
                case 'totalOffers':
                    $totalOffers = OfertaRepo::countTotalOffersByEmpresa($empresaId);
                    echo json_encode(['success' => true, 'data' => $totalOffers]);
                    break;

                case 'totalSolicitudes':
                    $totalSolicitudes = OfertaRepo::countTotalSolicitudesByEmpresa($empresaId);
                    echo json_encode(['success' => true, 'data' => $totalSolicitudes]);
                    break;

                // --- Gráficos de Distribución ---
                case 'activeExpiredOffers':
                    $data = OfertaRepo::countActiveAndExpiredOffers($empresaId);
                    echo json_encode(['success' => true, 'data' => $data]);
                    break;

                case 'topOffers':
                    // Por defecto, limitamos a 5
                    $limit = $_GET['limit'] ?? 5; 
                    $data = OfertaRepo::findTopOffersBySolicitudesForEmpresa($empresaId, (int)$limit);
                    echo json_encode(['success' => true, 'data' => $data]);
                    break;

                case 'cyclesDistribution':
                    $data = OfertaRepo::countCyclesByEmpresa($empresaId);
                    echo json_encode(['success' => true, 'data' => $data]);
                    break;
                
                // --- Gráfico de Tendencia ---
                case 'monthlyTrend':
                    $data = OfertaRepo::getSolicitudesMonthlyTrend($empresaId);
                    echo json_encode(['success' => true, 'data' => $data]);
                    break;

                default:
                    // Si el 'statType' no coincide con ninguno de los cases.
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Endpoint de estadística no válido.']);
                    break;
            }
        } catch (PDOException $e) {
            http_response_code(500);
            error_log("Error de base de datos en API: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error en el servidor al consultar la base de datos.']);
        } catch (Exception $e) {
             http_response_code(500);
            error_log("Error general en API: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor.']);
        }
        
        break;

    default:
        http_response_code(405); // Método no permitido
        echo json_encode(['success' => false, 'message' => 'Método HTTP no permitido.']);
        break;
}