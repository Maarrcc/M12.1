<?php

class ProfessorController {
    private $professorModel;
    private $assignaturaModel;
    private $pdo;

    public function __construct($pdo) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->pdo = $pdo;
        require_once '../app/models/Professor.php';
        $this->professorModel = new Professor($pdo);
    }

    public function assign() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        $professors = $this->professorModel->getAllProfessors();
        $assignatures = $this->professorModel->getAllAssignatures();
        
        require_once '../app/views/professors/assign.php';
    }

    public function store() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id_professor = $_POST['id_professor'] ?? null;
                $id_assignatura = $_POST['id_assignatura'] ?? null;

                if (!$id_professor || !$id_assignatura) {
                    throw new Exception("Faltan datos requeridos");
                }

                $result = $this->professorModel->assignProfessorToAssignatura($id_professor, $id_assignatura);
                
                if ($result) {
                    $_SESSION['success'] = 'Profesor asignado correctamente';
                    header('Location: /M12.1/my-app/public/index.php?controller=horari&action=index');
                } else {
                    throw new Exception("Error al asignar el profesor");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: /M12.1/my-app/public/index.php?controller=professor&action=assign');
            }
            exit;
        }
    }

    public function new() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        // Obtener usuarios que no son profesores
        require_once '../app/models/Usuari.php';
        $usuariModel = new Usuari($this->pdo);
        $usuaris = $usuariModel->getUsuarisNoProfessors();
        
        require_once '../app/views/professors/create.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Si no es POST, redirigir a new()
            $this->new();
            return;
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        try {
            $id_usuari = $_POST['id_usuari'] ?? null;

            if (!$id_usuari) {
                throw new Exception("Falten dades requerides");
            }

            $result = $this->professorModel->assignUsuariToProfessor($id_usuari);
            
            if ($result) {
                $_SESSION['success'] = 'Professor creat correctament';
                header('Location: /M12.1/my-app/public/index.php?controller=horari&action=index');
            } else {
                throw new Exception("Error al crear el professor");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /M12.1/my-app/public/index.php?controller=professor&action=new');
        }
        exit;
    }
}