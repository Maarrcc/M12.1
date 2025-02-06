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
        $sql = "SELECT id_usuari, contrasenya FROM usuaris WHERE nom_usuari = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'Usuario no encontrado'];
        }

        if (hash('sha256', $password) !== $user['contrasenya']) {
            return ['success' => false, 'message' => 'Contraseña incorrecta'];
        }

        return ['success' => true, 'user' => $user];
    }
}