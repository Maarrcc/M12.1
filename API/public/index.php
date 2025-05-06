<?php
// Permitir solicitudes 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');
    header('Access-Control-Max-Age: 86400'); // 24 horas
    exit(0);
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');

require_once '../config/config.php';
require_once '../app/models/Database.php';

// Función auxiliar para obtener headers
function getRequestHeaders() {
    if (function_exists('getallheaders')) {
        return getallheaders();
    }
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    return $headers;
}

// Verificar API Key
$headers = getRequestHeaders();
// Intentar obtener la API key de diferentes fuentes
$apiKey = isset($headers['X-Api-Key']) ? $headers['X-Api-Key'] : 
         (isset($headers['X-API-KEY']) ? $headers['X-API-KEY'] : 
         (isset($_SERVER['HTTP_X_API_KEY']) ? $_SERVER['HTTP_X_API_KEY'] : 
         (isset($_GET['api_key']) ? $_GET['api_key'] : null))); 

// Debug 
error_log('API Key recibida: ' . ($apiKey ?? 'no proporcionada'));
error_log('API Key esperada: ' . API_KEY);

// Verificar si la API Key es válida
if (!$apiKey || $apiKey !== API_KEY) {
    http_response_code(401);
    echo json_encode(['error' => 'API Key inválida o no proporcionada']);
    exit;
}

// Router básico
$controller = $_GET['controller'] ?? 'horari';
$action = $_GET['action'] ?? 'index';

// Cargar el controlador apropiado
$controllerFile = "../app/controllers/{$controller}Controller.php";
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerName = ucfirst($controller) . 'Controller';
    $controllerInstance = new $controllerName(Database::getInstance());
    
    if (method_exists($controllerInstance, $action)) {
        $controllerInstance->$action();
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Acción no encontrada']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Controlador no encontrado']);
}