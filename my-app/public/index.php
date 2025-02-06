<?php
session_start();
header('Access-Control-Allow-Origin: *');

if (
    !isset($_SESSION['user']) &&
    !($_GET['controller'] === 'auth' && ($_GET['action'] === 'login' || $_GET['action'] === 'validate'))
) {
    header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
    exit;
}

$config = require_once '../app/config/database.php';

try {
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['user'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

require_once '../app/controllers/HorariController.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/models/Horari.php';
require_once '../app/models/Usuari.php';

$action = $_GET['action'] ?? 'login';
$controller = $_GET['controller'] ?? 'auth';

try {
    switch ($controller) {
        case 'horari':
            $controller = new HorariController($pdo);
            break;
        case 'auth':
            $controller = new AuthController($pdo);
            break;
        default:
            throw new Exception('Controlador no encontrado');
    }

    $controller->$action();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}