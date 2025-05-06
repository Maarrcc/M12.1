<?php
require_once '../app/models/Horari.php';

class HorariController
{
    private $horariModel;
    private $requestMethod;
    private $cursId;

    public function __construct($db)
    {
        $this->horariModel = new Horari($db);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->cursId = isset($_GET['curs']) ? $_GET['curs'] : null;
    }

    public function index()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->cursId) {
                    $response = $this->horariModel->getByCurs($this->cursId);
                } else {
                    $response = $this->horariModel->getAll();
                }
                echo json_encode(['success' => true, 'data' => $response]);
                break;

            case 'POST':
                $input = (array) json_decode(file_get_contents('php://input'), true);
                if (!$this->validateInput($input)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
                    return;
                }
                try {
                    $id = $this->horariModel->create($input);
                    http_response_code(201);
                    echo json_encode(['success' => true, 'message' => 'Horario creado', 'id' => $id]);
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
                break;

            case 'PUT':
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
                    return;
                }

                $input = (array) json_decode(file_get_contents('php://input'), true);
                if (!$this->validateInput($input)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
                    return;
                }

                try {
                    $success = $this->horariModel->update($id, $input);
                    if ($success) {
                        echo json_encode(['success' => true, 'message' => 'Horario actualizado']);
                    } else {
                        http_response_code(404);
                        echo json_encode(['success' => false, 'message' => 'Horario no encontrado']);
                    }
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
                break;

            case 'DELETE':
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
                    return;
                }

                try {
                    $success = $this->horariModel->delete($id);
                    if ($success) {
                        echo json_encode(['success' => true, 'message' => 'Horario eliminado']);
                    } else {
                        http_response_code(404);
                        echo json_encode(['success' => false, 'message' => 'Horario no encontrado']);
                    }
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                break;
        }
    }

    private function validateInput($input)
    {
        $requiredFields = [
            'id_assignatura',
            'id_professor',
            'id_aula',
            'id_curs',
            'dia',
            'hora_inici',
            'hora_fi'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                return false;
            }
        }

        return true;
    }
}