<?php

class AlumnesController {
    private $alumneModel;
    private $usuariModel;
    private $cursModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->alumneModel = new Alumne($pdo);
        $this->usuariModel = new Usuari($pdo);
        $this->cursModel = new Curs($pdo);
    }

    public function assign() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        // Obtener usuarios que no son alumnos
        $usuaris = $this->usuariModel->getUsuarisNoAlumnes();
        $cursos = $this->cursModel->getAll();
        
        // Obtener cursos actuales si es un alumno
        $cursosActuals = [];
        if ($_SESSION['user']['rol'] === 'alumne') {
            $stmt = $this->pdo->prepare("
                SELECT c.* 
                FROM Cursos c 
                JOIN Alumnes a ON c.id_curs = a.id_curs 
                WHERE a.id_usuari = ?
            ");
            $stmt->execute([$_SESSION['user']['id_usuari']]);
            $cursosActuals = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        require_once '../app/views/alumnes/assign.php';
    }

    public function store() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuari = $_POST['id_usuari'] ?? null;
            $id_curs = $_POST['id_curs'] ?? null;

            if (!$id_usuari || !$id_curs) {
                $_SESSION['error'] = 'Falten dades requerides';
                header('Location: /M12.1/my-app/public/index.php?controller=alumnes&action=assign');
                exit;
            }

            try {
                $result = $this->alumneModel->assignUsuariToAlumne($id_usuari, $id_curs);
                if ($result) {
                    $_SESSION['success'] = 'Usuari assignat com alumne correctament';
                    header('Location: /M12.1/my-app/public/index.php?controller=horari&action=index');
                } else {
                    throw new Exception("Error al assignar l'usuari");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: /M12.1/my-app/public/index.php?controller=alumnes&action=assign');
            }
            exit;
        }

        // Si no es POST, redirigir a la página de asignación
        header('Location: /M12.1/my-app/public/index.php?controller=alumnes&action=assign');
        exit;
    }
}