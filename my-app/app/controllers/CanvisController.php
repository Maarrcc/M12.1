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

    public function create()
    {
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

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                if (empty($_POST['id_horari'])) {
                    throw new Exception("Es necesario seleccionar un horario");
                }
                if (empty($_POST['data_canvi'])) {
                    throw new Exception("La fecha de cambio es obligatoria");
                }
                if (empty($_POST['tipus_canvi'])) {
                    throw new Exception("El tipo de cambio es obligatorio");
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

    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        try {
            $canvis = $this->canviModel->getAllCanvis();
            require_once '../app/views/canvis/index.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar los cambios: ' . $e->getMessage();
            header('Location: /M12.1/my-app/public/index.php');
            exit;
        }
    }

    private function enviarNotificacionCambio($data)
    {
        require_once '../vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.dondominio.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@racogamer.cat';
            $mail->Password = 'MarcAlvero25-'; // NOTA: Usar variable de entorno a ser posible
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $detallesCambio = $this->canviModel->obtenerDetallesCambio(
                $data['id_horari'],
                $data['id_aula_substituta'],
                $data['id_professor_substitut']
            );

            $mail->setFrom('info@racogamer.cat', 'Sistema de Horarios');
            $mail->addAddress('marcalvero@insestatut.cat');

            $mail->isHTML(true);
            $mail->Subject = 'Nou canvi en l\'horari - ' . $data['tipus_canvi'];

            $bodyHtml = '
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; color: #333; }
                    .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                    h2 { color: #2c3e50; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .highlight { color: #e74c3c; font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2>S\'ha registrat un nou canvi en l\'horari</h2>
                    <table>
                        <tr><th>Tipus de canvi</th><td>' . htmlspecialchars($data['tipus_canvi']) . '</td></tr>
                        <tr><th>Data d\'inici</th><td>' . htmlspecialchars($data['data_canvi']) . '</td></tr>
                        <tr><th>Data de fi</th><td>' . ($data['data_fi'] ?: 'No especificada') . '</td></tr>
                        <tr><th>Curs</th><td>' . htmlspecialchars($detallesCambio['curs']) . '</td></tr>
                        <tr><th>Assignatura</th><td>' . htmlspecialchars($detallesCambio['assignatura']) . '</td></tr>
                        <tr><th>Professor</th><td>' . htmlspecialchars($detallesCambio['professor']) . '</td></tr>';

            if ($data['tipus_canvi'] === 'Canvi aula' && $data['id_aula_substituta']) {
                $bodyHtml .= '
                        <tr><th>Aula Original</th><td>' . htmlspecialchars($detallesCambio['aula_original']) . '</td></tr>
                        <tr><th>Aula Substituta</th><td class="highlight">' . htmlspecialchars($detallesCambio['aula_substituta']) . '</td></tr>';
            }

            if ($data['tipus_canvi'] === 'Canvi professor' && $data['id_professor_substitut']) {
                $bodyHtml .= '
                        <tr><th>Professor Substitut</th><td class="highlight">' . htmlspecialchars($detallesCambio['professor_substitut']) . '</td></tr>';
            }

            $bodyHtml .= '
                        <tr><th>Descripció</th><td>' . htmlspecialchars($data['descripcio_canvi']) . '</td></tr>
                    </table>
                    <p style="font-size: 0.9em; color: #777;">Aquest missatge és automàtic, no cal que hi responguis.</p>
                </div>
            </body>
            </html>';

            $mail->Body = $bodyHtml;
            $mail->AltBody = strip_tags($bodyHtml);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo: {$mail->ErrorInfo}");
            return false;
        }
    }
    public function getHorarisByDia()
    {
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

    public function getHorarisByCurs()
    {
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
                $horaris = array_filter($horaris, function ($horari) use ($dia) {
                    return $horari['dia'] === $dia;
                });
            }

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => array_values($horaris)
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}