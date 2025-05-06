<?php
// Permitir solicitudes CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');
    header('Access-Control-Max-Age: 86400');
    exit(0);
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');

require_once '../config/config.php';
require_once '../app/models/Database.php';
require_once '../app/middleware/AuthMiddleware.php';

// Función auxiliar para obtener headers
function getRequestHeaders() {
    if (function_exists('getallheaders')) {
        return getallheaders();
    }
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
            $headers[$name] = $value;
        }
    }
    return $headers;
}

// Validar API Key excepto para OPTIONS
if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    AuthMiddleware::validateApiKey();
}

// Obtener la ruta y el controlador
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
$controller = 'horari'; // Controlador por defecto

// Buscar el controlador en la URI
foreach ($uri as $segment) {
    if ($segment === 'horari' || $segment === 'canvis') {
        $controller = $segment;
        break;
    }
}

// Cargar el controlador apropiado
$controllerFile = "../app/controllers/" . ucfirst($controller) . "Controller.php";
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerName = ucfirst($controller) . 'Controller';
    $controllerInstance = new $controllerName(Database::getInstance());
    
    if (method_exists($controllerInstance, 'index')) {
        $controllerInstance->index();
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Acción no encontrada']);
    }
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Controlador no encontrado']);
}