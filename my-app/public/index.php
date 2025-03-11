<?php
session_start();
header('Access-Control-Allow-Origin: *');

if (
    !isset($_SESSION['user']) &&
    !($_GET['controller'] === 'auth' && ($_GET['action'] === 'login' || $_GET['action'] === 'validate' || $_GET['action'] === 'register' || $_GET['action'] === 'store'))
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

require_once '../app/config/config.php';
require_once '../app/config/database.php';

// Cargar los modelos
require_once '../app/models/Assignatura.php';
require_once '../app/models/Horari.php';
require_once '../app/models/Usuari.php';
require_once '../app/models/Aula.php';
require_once '../app/models/Alumnes.php';
require_once '../app/models/Curs.php';
require_once '../app/models/Professor.php';
require_once '../app/models/Canvi.php';

// Cargar los controladores
require_once '../app/controllers/AssignaturesController.php';
require_once '../app/controllers/HorariController.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/AulaController.php';
require_once '../app/controllers/AlumnesController.php';
require_once '../app/controllers/ProfessorController.php';
require_once '../app/controllers/CanvisController.php';
require_once '../app/controllers/ImportController.php';

// Obtener el controlador y la acción de la URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Construir el nombre de la clase del controlador
$controllerName = ucfirst($controller) . 'Controller';

try {
    if (class_exists($controllerName)) {
        $controller = new $controllerName($pdo);
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            throw new Exception('Acción no encontrada');
        }
    } else {
        throw new Exception('Controlador no encontrado');
    }
} catch (Exception $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
}