<?php

class AssignaturesController {
    private $assignaturaModel;
    
    public function __construct($pdo) {
        $this->assignaturaModel = new Assignatura($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }
        
        $assignatures = $this->assignaturaModel->getAll();
        require_once '../app/views/assignatures/index.php';
    }

    public function create() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }
        require_once '../app/views/assignatures/create.php';
    }

    public function store() {
        // Verificar si la sesión está iniciada
        if (!isset($_SESSION)) {
            session_start();
        }

        // Verificar si el usuario está autenticado y es admin
        if (!isset($_SESSION['user']) || !is_array($_SESSION['user']) || !isset($_SESSION['user']['rol']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar que existan los datos necesarios
                if (empty($_POST['nom']) || !isset($_POST['descripcio']) || empty($_POST['hores'])) {
                    throw new Exception("Faltan datos requeridos");
                }

                // Sanitizar los datos de entrada
                $assignatura = [
                    'nom' => htmlspecialchars(strip_tags($_POST['nom'])),
                    'descripcio' => htmlspecialchars(strip_tags($_POST['descripcio'])),
                    'hores' => (int)$_POST['hores']
                ];

                // Validar que las horas sean un número positivo
                if ($assignatura['hores'] <= 0) {
                    throw new Exception("Las horas deben ser un número positivo");
                }

                $result = $this->assignaturaModel->create($assignatura);
                if ($result) {
                    $_SESSION['success'] = 'Assignatura creada correctamente';
                    header('Location: /M12.1/my-app/public/index.php?controller=assignatures&action=index');
                    exit;
                } else {
                    throw new Exception("Error al crear la assignatura");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
                header('Location: /M12.1/my-app/public/index.php?controller=assignatures&action=create');
                exit;
            }
        } else {
            header('Location: /M12.1/my-app/public/index.php?controller=assignatures&action=create');
            exit;
        }
    }
}