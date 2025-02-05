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
        // Implementar lógica de login
    }
}