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
        $stmt = $this->pdo->query("SELECT id_curs, nom_cicle, any_academic FROM Cursos ORDER BY nom_cicle, any_academic");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Cursos WHERE id_curs = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nomCicle, $anyAcademic)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Cursos (nom_cicle, any_academic) VALUES (?, ?)");
        return $stmt->execute([$nomCicle, $anyAcademic]);
    }

    public function update($id, $nomCicle, $anyAcademic)
    {
        $stmt = $this->pdo->prepare("UPDATE Cursos SET nom_cicle = ?, any_academic = ? WHERE id_curs = ?");
        return $stmt->execute([$nomCicle, $anyAcademic, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM Cursos WHERE id_curs = ?");
        return $stmt->execute([$id]);
    }
}