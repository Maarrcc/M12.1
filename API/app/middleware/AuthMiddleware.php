<?php
class AuthMiddleware {
    public static function validateApiKey() {
        $headers = getRequestHeaders();
        $apiKey = isset($headers['X-API-Key']) ? $headers['X-API-Key'] : null;

        if (!$apiKey || $apiKey !== API_KEY) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'API Key inválida o no proporcionada'
            ]);
            exit;
        }
    }
}