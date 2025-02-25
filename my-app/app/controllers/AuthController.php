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
            // Almacenar el array completo del usuario (incluyendo 'rol')
            $_SESSION['user'] = $result['user'];
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
    
    public function register()
    {
        session_start();
        require_once '../app/views/auth/sign-in.php';
    }

    public function store()
    {
        session_start();

        $nom_usuari = $_POST['nom_usuari'] ?? '';
        $nom = $_POST['nom'] ?? '';
        $email = $_POST['email'] ?? '';
        $contrasenya = $_POST['contrasenya'] ?? '';
        $rol = $_POST['rol'] ?? '';

        // Validación básica
        if (empty($nom_usuari) || empty($nom) || empty($email) || empty($contrasenya) || empty($rol)) {
            $_SESSION['error'] = 'Tots els camps són obligatoris';
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=register');
            exit;
        }

        // Validar que el rol sea válido
        $rols_permesos = ['admin', 'alumne', 'professor'];
        if (!in_array($rol, $rols_permesos)) {
            $_SESSION['error'] = 'Rol no vàlid';
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=register');
            exit;
        }

        $result = $this->usuariModel->create($nom_usuari, $nom, $email, $contrasenya, $rol);

        if ($result['success']) {
            $_SESSION['success'] = 'Usuari registrat correctament';
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=register');
        }
        exit;
    }
}