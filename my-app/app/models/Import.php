<?php

class Import {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function importarUsuaris($data) {
        try {
            error_log("Iniciando importación de usuario: " . implode(",", $data));
            
            // Validar el rol
            $rol = strtolower(trim($data[4]));
            if (!in_array($rol, ['admin', 'alumne', 'professor'])) {
                throw new Exception("Rol no válido: {$rol}");
            }
            
            // Verificar si el usuario ya existe
            $stmt = $this->pdo->prepare("SELECT id_usuari FROM usuaris WHERE nom_usuari = ? OR email = ?");
            $stmt->execute([$data[0], $data[2]]);
            if ($stmt->fetch()) {
                error_log("Usuario ya existe: " . $data[0]);
                throw new Exception("L'usuari o email ja existeix: " . $data[0]);
            }

            // Usar password_hash
            $hashedPassword = password_hash($data[3], PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO usuaris (nom_usuari, nom, email, contrasenya, rol) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            
            // Reemplazar la contraseña original por la hasheada
            $data[3] = $hashedPassword;
            
            error_log("Ejecutando inserción con datos: " . implode(",", $data));
            $result = $stmt->execute($data);
            
            if (!$result) {
                error_log("Error en la inserción: " . print_r($stmt->errorInfo(), true));
                throw new Exception("Error al insertar usuario");
            }
            
            $id = $this->pdo->lastInsertId();
            error_log("Usuario insertado exitosamente con ID: " . $id);
            return $id;
        } catch (PDOException $e) {
            error_log("Error PDO en importarUsuaris: " . $e->getMessage());
            throw new Exception("Error al importar usuari: " . $e->getMessage());
        }
    }

    public function importarAlumne($idUsuari, $idCurs) {
        try {
            $sql = "INSERT INTO alumnes (id_usuari, id_curs) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idUsuari, $idCurs]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error al importar alumne: " . $e->getMessage());
        }
    }

    public function importarProfessor($idUsuari) {
        try {
            $sql = "INSERT INTO professors (id_usuari) VALUES (?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idUsuari]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error al importar professor: " . $e->getMessage());
        }
    }
}