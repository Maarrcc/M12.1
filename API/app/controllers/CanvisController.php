<?php
require_once '../app/models/Canvi.php';

class CanvisController {
    private $canviModel;
    private $requestMethod;

    public function __construct($db) {
        $this->canviModel = new Canvi($db);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    public function index() {
        try {
            $cursComplet = isset($_GET['curs']) ? $_GET['curs'] : null;
            $start = isset($_GET['start']) ? $_GET['start'] : null;
            $end = isset($_GET['end']) ? $_GET['end'] : null;

            if ($cursComplet && $start && $end) {
                $response = $this->canviModel->getCanvisPeriode($cursComplet, $start, $end);
            } else {
                $response = $this->canviModel->getAllCanvis();
            }

            // Asegurar que todos los cambios tengan los campos requeridos
            $response = array_map(function($canvi) {
                return array_merge([
                    'id_horari' => $canvi['id_horari'],
                    'tipus_canvi' => $canvi['tipus_canvi'],
                    'data_canvi' => $canvi['data_canvi'],
                    'data_fi' => $canvi['data_fi'] ?? null,
                    'descripcio_canvi' => $canvi['descripcio_canvi'] ?? '',
                    'estat' => $canvi['estat'] ?? 'actiu',
                    'professor_original' => $canvi['professor_original'] ?? null,
                    'professor_substitut' => $canvi['professor_substitut'] ?? null,
                    'aula_original' => $canvi['aula_original'] ?? null,
                    'aula_substituta' => $canvi['aula_substituta'] ?? null
                ], $canvi);
            }, $response);
            
            echo json_encode([
                'success' => true,
                'data' => $response
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function validateInput($input) {
        $requiredFields = [
            'id_horari',
            'tipus_canvi',
            'data_canvi'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                return false;
            }
        }

        return true;
    }
}