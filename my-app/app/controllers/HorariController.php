<?php
// controllers/HorariController.php 
class HorariController
{
    private $horariModel;

    public function __construct($pdo)
    {
        session_start();
        $this->horariModel = new Horari($pdo);
    }

    public function index()
    {
        // Verificar autenticación para la vista
        if (!isset($_SESSION['user'])) {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }
        require_once '../app/views/horari/index.php';
    }

    public function getHorari()
    {
        header('Content-Type: application/json');

        // Verificar autenticación
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'error' => 'No autorizado',
                'redirect' => '/M12.1/my-app/public/index.php?controller=auth&action=login'
            ]);
            exit;
        }

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
            echo json_encode([
                'error' => 'Error interno',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}