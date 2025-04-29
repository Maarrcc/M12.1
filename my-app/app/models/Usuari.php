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
        $sql = "SELECT u.* FROM Usuaris u 
                LEFT JOIN Alumnes a ON u.id_usuari = a.id_usuari 
                WHERE a.id_alumne IS NULL AND u.rol = 'alumne'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsuarisNoProfessors()
    {
        $sql = "SELECT u.* FROM Usuaris u 
                LEFT JOIN Professors p ON u.id_usuari = p.id_usuari 
                WHERE p.id_professor IS NULL AND u.rol = 'professor'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}