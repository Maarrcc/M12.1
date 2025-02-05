<?php
// controllers/HorariController.php 
class HorariController
{
    private $horariModel;

    public function __construct($pdo)
    {
        $this->horariModel = new Horari($pdo);
    }

    public function index()
    {
        require_once '../app/views/horari/index.php';
    }

    public function getHorari()
    {
        header('Content-Type: application/json');
        
        try {
            $curs = isset($_GET['curs']) ? htmlspecialchars($_GET['curs']) : 'DAW';
            $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : date('Y-m-d');
            $end = isset($_GET['end']) ? htmlspecialchars($_GET['end']) : date('Y-m-d');

            $horariBase = $this->horariModel->getHorariBase($curs);
            $canvis = $this->horariModel->getCanvis($start, $end, $curs);
            
            echo json_encode([
                'horari' => $horariBase,
                'canvis' => $canvis
            ]);
        } catch (Exception $e) {
            error_log("Error en getHorari: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'error' => 'Error interno del servidor',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }
}