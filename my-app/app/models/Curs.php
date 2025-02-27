<?php

class Curs
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM Cursos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCursByID($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Cursos WHERE id_curs = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}