<?php
// controllers/AuthController.php
class AuthController
{
    private $usuariModel;

    public function __construct($pdo)
    {
        $this->usuariModel = new Usuari($pdo);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Location: /M12.1/my-app/public/index.php?controller=horari&action=index');
            exit;
        }
        require_once '../app/views/auth/login.php';
    }
}