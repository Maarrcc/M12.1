<?php

class Assignatura
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM assignatures");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($assignatura)
    {
        $sql = "INSERT INTO assignatures (nom, descripcio, hores) VALUES (:nom, :descripcio, :hores)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':nom' => $assignatura['nom'],
            ':descripcio' => $assignatura['descripcio'],
            ':hores' => $assignatura['hores']
        ]);
    }
}