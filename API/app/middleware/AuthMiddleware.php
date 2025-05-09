<?php
class AuthMiddleware {
    public static function validateApiKey() {
        $apiKey = null;
        
        // Intentar obtener API Key de diferentes fuentes
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            $apiKey = isset($headers['X-API-Key']) ? $headers['X-API-Key'] : null;
        }
        
        // Si no se encontró en getallheaders(), buscar en $_SERVER
        if (!$apiKey && isset($_SERVER['HTTP_X_API_KEY'])) {
            $apiKey = $_SERVER['HTTP_X_API_KEY'];
        }

        if (!$apiKey || $apiKey !== 'ApiPrueba') {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'API Key inválida o no proporcionada'
            ]);
            exit;
        }
    }
}