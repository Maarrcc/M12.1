<?php

require_once '../app/models/Canvi.php';

class CanviController {
    private $canviModel;

    public function __construct($pdo) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->canviModel = new Canvi($pdo);
    }

    public function create() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        $professors = $this->canviModel->getProfessors();
        $aules = $this->canviModel->getAules();
        $cursos = $this->canviModel->getCursosDisponibles();
        
        require_once '../app/views/canvis/create.php';
    }

    public function store() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['id_curs'])) {
                    throw new Exception("Es necesario seleccionar un horario");
                }

                $data = [
                    'id_horari' => $_POST['id_horari'],
                    'id_curs' => $_POST['id_curs'],
                    'tipus_canvi' => $_POST['tipus_canvi'],
                    'data_canvi' => $_POST['data_canvi'],
                    'data_fi' => $_POST['data_fi'] ?? null,
                    'id_professor_substitut' => $_POST['id_professor_substitut'] ?? null,
                    'id_aula_substituta' => $_POST['id_aula_substituta'] ?? null,
                    'descripcio_canvi' => $_POST['descripcio_canvi']
                ];

                $result = $this->canviModel->insertCanvi($data);
                
                if ($result) {
                    $_SESSION['success'] = 'Cambio registrado correctamente';
                    header('Location: /M12.1/my-app/public/index.php?controller=horari&action=index');
                } else {
                    throw new Exception("Error al registrar el cambio");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: /M12.1/my-app/public/index.php?controller=canvi&action=create');
            }
            exit;
        }
    }

    public function getHorarisByDia() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $dia = $_GET['dia'] ?? '';
        if (empty($dia)) {
            echo json_encode(['error' => 'Día no especificado']);
            exit;
        }

        $horaris = $this->canviModel->getHorarisByDia($dia);
        echo json_encode(['horaris' => $horaris]);
        exit;
    }

    public function getHorarisByCurs() {
        if (!isset($_GET['curs']) || !isset($_GET['dia'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Falten paràmetres']);
            return;
        }

        $idCurs = $_GET['curs'];
        $dia = $_GET['dia'];
        
        try {
            // Usar canviModel
            $horaris = $this->canviModel->getHorarisByCurs($idCurs);
            
            // Filtrar por día si se especifica
            if ($dia) {
                $horaris = array_filter($horaris, function($horari) use ($dia) {
                    return $horari['dia'] === $dia;
                });
            }
            
            header('Content-Type: application/json');
            echo json_encode(array_values($horaris));
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}