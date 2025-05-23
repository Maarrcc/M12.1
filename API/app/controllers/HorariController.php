<?php
require_once '../app/models/Horari.php';

class HorariController
{
    private $horariModel;

    public function __construct($db)
    {
        $this->horariModel = new Horari($db);
    }

    public function index()
    {
        try {
            $cursComplet = isset($_GET['curs']) ? $_GET['curs'] : null;
            $dia = isset($_GET['dia']) ? $_GET['dia'] : null;

            if ($cursComplet && $dia) {
                $response = $this->horariModel->getByCursAndDia($cursComplet, $dia);
            } elseif ($cursComplet) {
                $response = $this->horariModel->getByCurs($cursComplet);
            } else {
                $response = $this->horariModel->getAll();
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