<?php
// Configuración de CORS y headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');
    header('Access-Control-Max-Age: 86400');
    exit(0);
}

require_once '../config/config.php';
require_once '../app/models/Database.php';
require_once '../app/middleware/AuthMiddleware.php';

// Detectar si la petición es del navegador
$isBrowser = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'text/html') !== false;

// Si es una petición del navegador y es GET, establecer Content-Type como HTML y mostrar documentación
if ($isBrowser && $_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: text/html; charset=UTF-8');
    include_once '../app/views/api_docs.php';
    exit;
}

// Para todas las demás peticiones, validar API Key
if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    AuthMiddleware::validateApiKey();
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');

try {
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

    if (!file_exists($controllerFile)) {
        throw new Exception('Controlador no encontrado');
    }

    require_once $controllerFile;
    $controllerName = ucfirst($controller) . 'Controller';
    $controllerInstance = new $controllerName(Database::getInstance());
    
    if (!method_exists($controllerInstance, 'index')) {
        throw new Exception('Acción no encontrada');
    }

    $controllerInstance->index();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'trace' => DEBUG_MODE ? $e->getTraceAsString() : null
    ]);
}