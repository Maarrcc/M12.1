<?php

class AssignaturesAlumnesController {
    private $pdo;
    private $model;

    public function __construct($pdo) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->pdo = $pdo;
        $this->model = new AssignaturesAlumnes($pdo);
    }

    public function index() {
        try {
            if (!isset($_SESSION['user'])) {
                $_SESSION['error'] = 'Has d\'iniciar sessió per accedir a aquesta pàgina';
                header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
                exit;
            }

            $isAdmin = $_SESSION['user']['rol'] === 'admin';
            $currentUserId = $_SESSION['user']['id_usuari'];
            
            // Si es admin, obtiene todas las matrículas, si no, solo las del usuario
            $matricules = $this->model->getAllMatricules($isAdmin ? null : $currentUserId);
            
            // Verificar si el usuario es alumno y obtener su curso
            $curs = null;
            if ($_SESSION['user']['rol'] === 'alumne') {
                $curs = $this->model->getCursByAlumne($currentUserId);
                if (!$curs) {
                    $_SESSION['error'] = 'No estàs matriculat en cap curs';
                    require_once '../app/views/matricules/assign.php';
                    return;
                }
                $assignatures = $this->model->getAssignaturesByCurs($curs['id_curs']);
            } else {
                $assignatures = $this->model->getAllAssignatures();
            }

            $alumnes = $isAdmin ? $this->model->getAllAlumnes() : [];
            
            require_once '../app/views/matricules/assign.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /M12.1/my-app/public/index.php');
            exit;
        }
    }

    public function toggleNotificacions() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=index');
            exit;
        }

        try {
            if (empty($_POST['id_alumne']) || empty($_POST['id_assignatura'])) {
                throw new Exception('Falten dades');
            }

            // Verificar que el usuario solo puede modificar sus propias notificaciones
            $isAdmin = $_SESSION['user']['rol'] === 'admin';
            if (!$isAdmin) {
                $currentUserId = $_SESSION['user']['id_usuari'];
                $matricules = $this->model->getAllMatricules($currentUserId);
                $authorized = false;
                foreach ($matricules as $matricula) {
                    if ($matricula['id_alumne'] == $_POST['id_alumne']) {
                        $authorized = true;
                        break;
                    }
                }
                if (!$authorized) {
                    throw new Exception('No tens permís per modificar aquestes notificacions');
                }
            }

            $this->model->toggleNotificacions($_POST['id_alumne'], $_POST['id_assignatura']);
            $_SESSION['success'] = 'Preferències de notificacions actualitzades correctament';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }

        header('Location: /M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=index');
        exit;
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=index');
            exit;
        }

        try {
            if (empty($_POST['id_alumne']) || empty($_POST['assignatures'])) {
                throw new Exception('Selecciona un alumne i almenys una assignatura');
            }

            $idAlumne = $_POST['id_alumne'];
            $assignatures = $_POST['assignatures'];

            foreach ($assignatures as $idAssignatura => $data) {
                if (isset($data['id'])) {
                    $rebreNotificacions = isset($data['notificacions']) ? true : false;
                    $this->model->matricularAlumne($idAlumne, $data['id'], $rebreNotificacions);
                }
            }

            $_SESSION['success'] = 'Matrícula realitzada correctament';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al realitzar la matrícula: ' . $e->getMessage();
        }

        header('Location: /M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=index');
        exit;
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=index');
            exit;
        }

        try {
            if (empty($_POST['id_alumne']) || empty($_POST['id_assignatura'])) {
                throw new Exception('Dades incompletes');
            }

            $this->model->deleteMatricula($_POST['id_alumne'], $_POST['id_assignatura']);
            $_SESSION['success'] = 'Matrícula eliminada correctament';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error en eliminar la matrícula: ' . $e->getMessage();
        }

        header('Location: /M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=index');
        exit;
    }
}