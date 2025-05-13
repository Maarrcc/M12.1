<?php
// models/Usuari.php
class Usuari
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function login($username, $password)
    {
        $sql = "SELECT id_usuari, nom_usuari, contrasenya, rol FROM usuaris WHERE nom_usuari = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'Usuario no encontrado'];
        }

        if (!password_verify($password, $user['contrasenya'])) {
            return ['success' => false, 'message' => 'Contraseña incorrecta'];
        }

        return ['success' => true, 'user' => $user];
    }

    public function create($nom_usuari, $nom, $email, $contrasenya, $rol)
    {
        try {
            // Verificar si el usuario ya existe
            $stmt = $this->pdo->prepare("SELECT id_usuari FROM usuaris WHERE nom_usuari = ? OR email = ?");
            $stmt->execute([$nom_usuari, $email]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'El nom d\'usuari o email ja existeix'];
            }

            // Insertar nuevo usuario
            $sql = "INSERT INTO usuaris (nom_usuari, nom, email, contrasenya, rol) VALUES (?, ?, ?, ?, ?)";
            $hashedPassword = password_hash($contrasenya, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nom_usuari, $nom, $email, $hashedPassword, $rol]);

            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error al crear l\'usuari: ' . $e->getMessage()];
        }
    }

    public function getUsuarisNoAlumnes()
    {
        $sql = "SELECT u.* FROM usuaris u 
                LEFT JOIN alumnes a ON u.id_usuari = a.id_usuari 
                WHERE a.id_alumne IS NULL AND u.rol = 'alumne'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsuarisNoProfessors()
    {
        $sql = "SELECT u.* FROM usuaris u 
                LEFT JOIN professors p ON u.id_usuari = p.id_usuari 
                WHERE p.id_professor IS NULL AND u.rol = 'professor'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsuaris() {
        $sql = "SELECT * FROM usuaris ORDER BY nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        try {
            // Primero verificar si el usuario existe
            $stmt = $this->pdo->prepare("SELECT rol FROM usuaris WHERE id_usuari = ?");
            $stmt->execute([$id]);
            $usuari = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$usuari) {
                throw new Exception("Usuari no trobat");
            }

            // Iniciar transacción
            $this->pdo->beginTransaction();

            // Eliminar registros relacionados según el rol
            if ($usuari['rol'] === 'alumne') {
                $stmt = $this->pdo->prepare("DELETE FROM assignatures_alumnes WHERE id_alumne IN (SELECT id_alumne FROM alumnes WHERE id_usuari = ?)");
                $stmt->execute([$id]);
                
                $stmt = $this->pdo->prepare("DELETE FROM alumnes WHERE id_usuari = ?");
                $stmt->execute([$id]);
            } elseif ($usuari['rol'] === 'professor') {
                $stmt = $this->pdo->prepare("DELETE FROM professors WHERE id_usuari = ?");
                $stmt->execute([$id]);
            }

            // Finalmente eliminar el usuario
            $stmt = $this->pdo->prepare("DELETE FROM usuaris WHERE id_usuari = ?");
            $stmt->execute([$id]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al eliminar l'usuari: " . $e->getMessage());
        }
    }
}