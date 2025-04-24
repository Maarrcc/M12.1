<?php

class HomeController {
    public function __construct($pdo) {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function index() {
        // Si el usuario ya está autenticado, redirigir al horario
        if (isset($_SESSION['user'])) {
            header('Location: /M12.1/my-app/public/index.php?controller=horari&action=index');
            exit;
        }
        
        require_once '../app/views/home/index.php';
    }
}