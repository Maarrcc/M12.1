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
}