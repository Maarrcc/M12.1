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
        $stmt = $this->pdo->query("SELECT id_curs, nom_cicle, any_academic FROM cursos ORDER BY nom_cicle, any_academic");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cursos WHERE id_curs = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nomCicle, $anyAcademic)
    {
        $stmt = $this->pdo->prepare("INSERT INTO cursos (nom_cicle, any_academic) VALUES (?, ?)");
        return $stmt->execute([$nomCicle, $anyAcademic]);
    }

    public function update($id, $nomCicle, $anyAcademic)
    {
        $stmt = $this->pdo->prepare("UPDATE cursos SET nom_cicle = ?, any_academic = ? WHERE id_curs = ?");
        return $stmt->execute([$nomCicle, $anyAcademic, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM cursos WHERE id_curs = ?");
        return $stmt->execute([$id]);
    }
}