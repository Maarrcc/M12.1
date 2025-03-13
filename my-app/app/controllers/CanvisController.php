<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CanvisController 
{
    private $canviModel;
    private $pdo;

    public function __construct($pdo) 
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->pdo = $pdo;
        $this->canviModel = new Canvi($pdo);
    }

    public function create() {
        try {
            // Obtener los datos necesarios para el formulario
            $cursos = $this->canviModel->getCursosDisponibles();
            $professors = $this->canviModel->getProfessors();
            $aules = $this->canviModel->getAules();
            
            // Pasar los datos a la vista
            require_once '../app/views/canvis/create.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar el formulario: ' . $e->getMessage();
            header('Location: /M12.1/my-app/public/index.php?controller=horari&action=index');
            exit;
        }
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['id_horari'])) {
                    throw new Exception("Es necesario seleccionar un horario");
                }

                $data = [
                    'id_horari' => $_POST['id_horari'],
                    'id_curs' => $_POST['id_curs'],
                    'tipus_canvi' => $_POST['tipus_canvi'],
                    'data_canvi' => $_POST['data_canvi'],
                    'data_fi' => $_POST['data_fi'],
                    'estat' => 'actiu',
                    'id_professor_substitut' => !empty($_POST['id_professor_substitut']) ? $_POST['id_professor_substitut'] : null,
                    'id_aula_substituta' => !empty($_POST['id_aula_substituta']) ? $_POST['id_aula_substituta'] : null,
                    'descripcio_canvi' => $_POST['descripcio_canvi']
                ];

                $result = $this->canviModel->insertCanvi($data);
                
                if ($result) {
                    // Solo intentar enviar el correo si la inserción fue exitosa
                    $mailSent = $this->enviarNotificacionCambio($data);
                    
                    $_SESSION['success'] = $mailSent ? 
                        'Cambio registrado y notificación enviada correctamente' : 
                        'Cambio registrado correctamente (no se pudo enviar la notificación)';
                    
                    header('Location: /M12.1/my-app/public/index.php?controller=horari&action=index');
                    exit;
                }

            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al registrar el cambio: ' . $e->getMessage();
                header('Location: /M12.1/my-app/public/index.php?controller=canvis&action=create');
                exit;
            }
        }
        
        // Si no es POST, redirigir al formulario
        header('Location: /M12.1/my-app/public/index.php?controller=canvis&action=create');
        exit;
    }

    private function enviarNotificacionCambio($data) {
        require_once '../vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'marcalvero@insestatut.cat';
            $mail->Password = 'ma_29942994';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $detallesCambio = $this->canviModel->obtenerDetallesCambio($data['id_horari']);

            $mail->setFrom('horari@tudominio.com', 'Sistema de Horarios');
            $mail->addAddress('marcalvero@insestatut.cat');

            $mail->isHTML(true);
            $mail->Subject = 'Nuevo cambio en el horario - ' . $data['tipus_canvi'];
            
            $bodyHtml = "
                <h2>Se ha registrado un nuevo cambio en el horario</h2>
                <p><strong>Tipo de cambio:</strong> {$data['tipus_canvi']}</p>
                <p><strong>Fecha:</strong> {$data['data_canvi']}</p>
                <p><strong>Descripción:</strong> {$data['descripcio_canvi']}</p>
                <p><strong>Curso:</strong> {$detallesCambio['curs']}</p>
                <p><strong>Asignatura:</strong> {$detallesCambio['assignatura']}</p>
                <p><strong>Profesor:</strong> {$detallesCambio['professor']}</p>
            ";

            $mail->Body = $bodyHtml;
            $mail->AltBody = strip_tags($bodyHtml);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function getHorarisByDia() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $dia = $_GET['dia'] ?? '';
        if (empty($dia)) {
            echo json_encode(['error' => 'Día no especificado']);
            exit;
        }

        $horaris = $this->canviModel->getHorarisByDia($dia);
        echo json_encode(['horaris' => $horaris]);
        exit;
    }

    public function getHorarisByCurs() {
        if (!isset($_GET['curs']) || !isset($_GET['dia'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan parámetros requeridos']);
            return;
        }

        $idCurs = $_GET['curs'];
        $dia = $_GET['dia'];

        try {
            $horaris = $this->canviModel->getHorarisByCurs($idCurs);
            
            // Filtrar por día si se especifica
            if ($dia) {
                $horaris = array_filter($horaris, function($horari) use ($dia) {
                    return $horari['dia'] === $dia;
                });
            }

            header('Content-Type: application/json');
            echo json_encode(array_values($horaris));
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}