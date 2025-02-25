<?php

class AulaController {
    private $aulaModel;

    public function __construct($pdo) {
        $this->aulaModel = new Aula($pdo);
    }

    public function index() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        $aules = $this->aulaModel->getAll();
        require_once '../app/views/aules/create.php';
    }

    public function create() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }
        require_once '../app/views/aules/create.php'; 
    }

    public function store() {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar que existan los datos necesarios
                if (empty($_POST['nom_aula']) || empty($_POST['capacitat'])) {
                    throw new Exception("Faltan datos requeridos");
                }
                
                // Sanitizar entradas
                $nom_aula = htmlspecialchars(strip_tags($_POST['nom_aula']));
                $capacitat = (int) $_POST['capacitat'];
                
                if ($capacitat <= 0) {
                    throw new Exception("La capacidad debe ser un número mayor a cero");
                }
                
                $result = $this->aulaModel->create($nom_aula, $capacitat);
                if ($result) {
                    $_SESSION['success'] = 'Aula creada correctamente';
                    header('Location: /M12.1/my-app/public/index.php?controller=aula&action=index');
                    exit;
                } else {
                    throw new Exception("Error al crear el aula");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
                header('Location: /M12.1/my-app/public/index.php?controller=aula&action=create');
                exit;
            }
        }
        header('Location: /M12.1/my-app/public/index.php?controller=aula&action=create');
        exit;
    }
}