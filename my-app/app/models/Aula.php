<?php

class Aula {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($nom_aula, $capacitat) {
        $sql = "INSERT INTO Aulas (nom_aula, capacitat) VALUES (:nom_aula, :capacitat)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':nom_aula' => $nom_aula,
            ':capacitat' => $capacitat
        ]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM Aulas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}