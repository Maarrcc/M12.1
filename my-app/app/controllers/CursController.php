<?php

class CursController {
    private $cursModel;
    private $pdo;

    public function __construct($pdo) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->pdo = $pdo;
        $this->cursModel = new Curs($pdo);
    }

    public function default() {
        $this->index();
    }

    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        $cursos = $this->cursModel->getAll();
        require_once '../app/views/cursos/index.php';
    }

    public function create() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }
        require_once '../app/views/cursos/create.php';
    }

    public function store() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_cicle = $_POST['nom_cicle'] ?? '';
            $any_academic = $_POST['any_academic'] ?? '';
            if (!$nom_cicle || !$any_academic) {
                $_SESSION['error'] = 'Tots els camps són obligatoris';
                header('Location: /M12.1/my-app/public/index.php?controller=curs&action=create');
                exit;
            }
            try {
                $stmt = $this->pdo->prepare("INSERT INTO cursos (nom_cicle, any_academic) VALUES (?, ?)");
                $stmt->execute([$nom_cicle, $any_academic]);
                $_SESSION['success'] = 'Curs creat correctament';
                header('Location: /M12.1/my-app/public/index.php?controller=curs');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al crear el curs: ' . $e->getMessage();
                header('Location: /M12.1/my-app/public/index.php?controller=curs&action=create');
                exit;
            }
        }
        header('Location: /M12.1/my-app/public/index.php?controller=curs');
        exit;
    }

    public function delete() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id_curs'];
                
                // Verificar si hay alumnes o horarios asociados
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM alumnes WHERE id_curs = ?");
                $stmt->execute([$id]);
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception('No es pot eliminar el curs perquè té alumnes assignats');
                }
                
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM horari WHERE id_curs = ?");
                $stmt->execute([$id]);
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception('No es pot eliminar el curs perquè té horaris assignats');
                }
                
                $stmt = $this->pdo->prepare("DELETE FROM cursos WHERE id_curs = ?");
                if ($stmt->execute([$id])) {
                    $_SESSION['success'] = 'Curs eliminat correctament';
                } else {
                    throw new Exception('Error al eliminar el curs');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error: ' . $e->getMessage();
            }
        }
        
        header('Location: /M12.1/my-app/public/index.php?controller=curs');
        exit;
    }
}