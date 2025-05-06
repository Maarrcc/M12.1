<?php
require_once '../app/models/Canvi.php';

class CanviController
{
    private $canviModel;

    public function __construct($db)
    {
        $this->canviModel = new Canvi($db);
    }

    public function index()
    {
        try {
            $cursComplet = isset($_GET['curs']) ? $_GET['curs'] : null;
            $start = isset($_GET['start']) ? $_GET['start'] : null;
            $end = isset($_GET['end']) ? $_GET['end'] : null;

            if ($cursComplet && $start && $end) {
                $response = $this->canviModel->getCanvisPeriode($cursComplet, $start, $end);
            } else {
                $response = $this->canviModel->getAllCanvis();
            }

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
}