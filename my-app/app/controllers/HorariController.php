<?php
// controllers/HorariController.php 
class HorariController {
    private $horariModel;
    private $pdo;

    public function __construct($pdo) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->pdo = $pdo;
        $this->horariModel = new Horari($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        // Obtener datos iniciales para el horario
        $cicles = ['DAW', 'DAM', 'ASIX'];
        $anys = ['Primer', 'Segon'];

        require_once '../app/views/horari/index.php';
    }

    public function create() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        $assignatures = $this->horariModel->getAllAssignatures();
        $professors = $this->horariModel->getAllProfessors();
        $aulas = $this->horariModel->getAllAulas();
        $cursos = $this->horariModel->getAllCursos();
        
        require_once '../app/views/horari/create.php';
    }

    public function store() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_assignatura' => $_POST['id_assignatura'],
                'id_professor' => $_POST['id_professor'],
                'id_aula' => $_POST['id_aula'],
                'id_curs' => $_POST['id_curs'],
                'dia' => $_POST['dia'],
                'hora_inici' => $_POST['hora_inici'],
                'hora_fi' => $_POST['hora_fi']
            ];

            if ($this->horariModel->create($data)) {
                $_SESSION['success'] = 'Horario creado correctamente';
            } else {
                $_SESSION['error'] = 'Error al crear el horario';
            }
            header('Location: /M12.1/my-app/public/index.php?controller=horari');
            exit;
        }
    }

    public function edit() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        $id = $_GET['id'];
        $horari = $this->horariModel->getById($id);
        $assignatures = $this->horariModel->getAllAssignatures();
        $professors = $this->horariModel->getAllProfessors();
        $aulas = $this->horariModel->getAllAulas();
        $cursos = $this->horariModel->getAllCursos();

        require_once '../app/views/horari/edit.php';
    }

    public function update() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_horari'];
            $data = [
                'id_assignatura' => $_POST['id_assignatura'],
                'id_professor' => $_POST['id_professor'],
                'id_aula' => $_POST['id_aula'],
                'id_curs' => $_POST['id_curs'],
                'dia' => $_POST['dia'],
                'hora_inici' => $_POST['hora_inici'],
                'hora_fi' => $_POST['hora_fi']
            ];

            if ($this->horariModel->update($id, $data)) {
                $_SESSION['success'] = 'Horario actualizado correctamente';
            } else {
                $_SESSION['error'] = 'Error al actualizar el horario';
            }
            header('Location: /M12.1/my-app/public/index.php?controller=horari');
            exit;
        }
    }

    public function delete() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_horari'];
            if ($this->horariModel->delete($id)) {
                $_SESSION['success'] = 'Horario eliminado correctamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar el horario';
            }
            header('Location: /M12.1/my-app/public/index.php?controller=horari');
            exit;
        }
    }

    public function getHorari() {
        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => true, 'redirect' => '/M12.1/my-app/public/index.php?controller=auth&action=login']);
            exit;
        }

        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;
        $curs = $_GET['curs'] ?? null;

        if (!$start || !$end || !$curs) {
            echo json_encode(['error' => 'Parámetros incorrectos']);
            exit;
        }

        try {
            $horariBase = $this->horariModel->getHorariBase($curs);
            $canvis = $this->horariModel->getCanvis($start, $end, $curs);
            
            echo json_encode([
                'horari' => $horariBase,
                'canvis' => $canvis
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function manage() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        try {
            $horaris = $this->horariModel->getAllHoraris();
            require_once '../app/views/horari/manage.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar los horarios: ' . $e->getMessage();
            header('Location: /M12.1/my-app/public/index.php');
            exit;
        }
    }
}