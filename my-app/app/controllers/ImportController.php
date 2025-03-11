<?php

require_once '../app/models/Import.php';

class ImportController {
    private $importModel;
    private $pdo;

    public function __construct($pdo) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->pdo = $pdo;
        $this->importModel = new Import($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tens permisos per realitzar aquesta acció';
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }
        
        require_once '../app/views/import/form.php';
    }

    public function importar() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tens permisos per realitzar aquesta acció';
            header('Location: /M12.1/my-app/public/index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->pdo->beginTransaction();

                if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== 0) {
                    throw new Exception("Error al carregar l'arxiu");
                }

                $tipo = $_POST['tipo_dato'];
                $file = fopen($_FILES['csv_file']['tmp_name'], 'r');
                
                error_log("Iniciando importación de tipo: " . $tipo);
                
                if ($file === false) {
                    throw new Exception("Error al llegir l'arxiu CSV");
                }

                $headers = fgetcsv($file);
                if ($headers === false) {
                    throw new Exception("El arxiu CSV està buit");
                }

                error_log("Headers del CSV: " . implode(",", $headers));

                // Procesamos cada línea
                $linea = 1;
                while (($data = fgetcsv($file)) !== FALSE) {
                    error_log("Procesando línea {$linea}: " . implode(",", $data));
                    
                    if (count($data) < 1) {
                        error_log("Línea {$linea} vacía, saltando...");
                        continue;
                    }

                    switch ($tipo) {
                        case 'usuaris':
                            if (count($data) !== 5) {
                                throw new Exception("Format incorrecte per usuaris en línia {$linea}");
                            }
                            $result = $this->importModel->importarUsuaris($data);
                            error_log("Resultado de importación de usuario línea {$linea}: " . ($result ? "OK" : "ERROR"));
                            break;

                        case 'alumnes':
                            if (count($data) !== 5) {
                                throw new Exception("Format incorrecte per alumnes");
                            }
                            $idUsuari = $this->importModel->importarUsuaris([
                                $data[0], // nom_usuari
                                $data[1], // nom
                                $data[2], // email
                                $data[3], // contrasenya
                                'alumne'  // rol
                            ]);
                            if ($idUsuari) {
                                $this->importModel->importarAlumne($idUsuari, $data[4]);
                            }
                            break;

                        case 'professors':
                            if (count($data) !== 4) {
                                throw new Exception("Format incorrecte per professors");
                            }
                            $idUsuari = $this->importModel->importarUsuaris([
                                $data[0], // nom_usuari
                                $data[1], // nom
                                $data[2], // email
                                $data[3], // contrasenya
                                'professor' // rol
                            ]);
                            if ($idUsuari) {
                                $this->importModel->importarProfessor($idUsuari);
                            }
                            break;

                        default:
                            throw new Exception("Tipus de dades no vàlid");
                    }
                    $linea++;
                }
                
                fclose($file);
                $this->pdo->commit();
                
                // Verificar la inserción
                $stmt = $this->pdo->query("SELECT * FROM Usuaris ORDER BY id_usuari DESC LIMIT 1");
                $ultimoUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
                error_log("Último usuario insertado: " . print_r($ultimoUsuario, true));

                $_SESSION['success'] = 'Dades importades correctament';
                
            } catch (Exception $e) {
                if ($file) {
                    fclose($file);
                }
                $this->pdo->rollBack();
                $_SESSION['error'] = $e->getMessage();
            }
            
            header('Location: /M12.1/my-app/public/index.php?controller=import&action=index');
            exit;
        }
    }
}