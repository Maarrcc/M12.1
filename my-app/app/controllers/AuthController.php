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
        session_start();
        require_once '../app/views/auth/login.php';
    }

    public function validate()
    {
        session_start();

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->usuariModel->login($username, $password);

        if ($result['success']) {
            $_SESSION['user'] = $username;
            header('Location: /M12.1/my-app/public/index.php?controller=horari&action=index');
            exit;
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
        exit;
    }
}