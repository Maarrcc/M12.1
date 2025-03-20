<?php

class AssignaturesAlumnesController {
    private $pdo;
    private $model;

    public function __construct($pdo) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->pdo = $pdo;
        $this->model = new AssignaturesAlumnes($pdo);
    }

    public function index() {
        try {
            if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
                $_SESSION['error'] = 'No tienes permisos para acceder a esta página';
                header('Location: /M12.1/my-app/public/index.php');
                exit;
            }

            $matricules = $this->model->getAllMatricules();
            $alumnes = $this->model->getAllAlumnes();
            $assignatures = $this->model->getAllAssignatures();

            require_once '../app/views/matricules/assign.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar las matrículas: ' . $e->getMessage();
            header('Location: /M12.1/my-app/public/index.php');
            exit;
        }
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=index');
            exit;
        }

        try {
            if (empty($_POST['id_alumne']) || empty($_POST['assignatures'])) {
                throw new Exception('Selecciona un alumno y al menos una asignatura');
            }

            $idAlumne = $_POST['id_alumne'];
            $assignatures = $_POST['assignatures'];

            foreach ($assignatures as $idAssignatura) {
                $this->model->matricularAlumne($idAlumne, $idAssignatura);
            }

            $_SESSION['success'] = 'Matrícula realizada correctamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al realizar la matrícula: ' . $e->getMessage();
        }

        header('Location: /M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=index');
        exit;
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=index');
            exit;
        }

        try {
            if (empty($_POST['id_alumne']) || empty($_POST['id_assignatura'])) {
                throw new Exception('Datos incompletos');
            }

            $this->model->deleteMatricula($_POST['id_alumne'], $_POST['id_assignatura']);
            $_SESSION['success'] = 'Matrícula eliminada correctamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar la matrícula: ' . $e->getMessage();
        }

        header('Location: /M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=index');
        exit;
    }
}