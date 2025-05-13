<?php
class UsuarisController {
    private $usuariModel;
    
    public function __construct($pdo) {
        $this->usuariModel = new Usuari($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        try {
            $usuaris = $this->usuariModel->getAllUsuaris();
            require_once '../app/views/usuaris/index.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar los usuarios: ' . $e->getMessage();
            header('Location: /M12.1/my-app/public/index.php');
            exit;
        }
    }

    public function delete() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id_usuari'];
                
                // No permitir eliminar el propio usuario
                if ($id == $_SESSION['user']['id_usuari']) {
                    throw new Exception('No pots eliminar el teu propi usuari');
                }

                if ($this->usuariModel->delete($id)) {
                    $_SESSION['success'] = 'Usuari eliminat correctament';
                } else {
                    $_SESSION['error'] = 'Error al eliminar l\'usuari';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error: ' . $e->getMessage();
            }
            
            header('Location: /M12.1/my-app/public/index.php?controller=usuaris&action=index');
            exit;
        }
    }
}