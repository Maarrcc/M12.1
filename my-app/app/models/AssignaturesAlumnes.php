<?php

class AssignaturesAlumnes {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllMatricules() {
        $sql = "SELECT aa.*, u.nom as nom_complet, ass.nom as nom_assignatura
                FROM Assignatures_Alumnes aa
                JOIN Alumnes a ON aa.id_alumne = a.id_alumne
                JOIN Usuaris u ON a.id_usuari = u.id_usuari
                JOIN Assignatures ass ON aa.id_assignatura = ass.id_assignatura
                ORDER BY u.nom, ass.nom";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllAlumnes() {
        $sql = "SELECT a.id_alumne, u.nom 
                FROM Alumnes a
                JOIN Usuaris u ON a.id_usuari = u.id_usuari
                ORDER BY u.nom";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllAssignatures() {
        $sql = "SELECT * FROM Assignatures ORDER BY nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function matricularAlumne($idAlumne, $idAssignatura) {
        // Verificar si ya existe la matrícula
        $sql = "SELECT COUNT(*) FROM Assignatures_Alumnes 
                WHERE id_alumne = :id_alumne AND id_assignatura = :id_assignatura";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_alumne' => $idAlumne,
            'id_assignatura' => $idAssignatura
        ]);
        
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("El alumno ya está matriculado en esta asignatura");
        }

        // Insertar nueva matrícula
        $sql = "INSERT INTO Assignatures_Alumnes (id_alumne, id_assignatura) 
                VALUES (:id_alumne, :id_assignatura)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_alumne' => $idAlumne,
            'id_assignatura' => $idAssignatura
        ]);
    }

    public function deleteMatricula($idAlumne, $idAssignatura) {
        $sql = "DELETE FROM Assignatures_Alumnes 
                WHERE id_alumne = :id_alumne AND id_assignatura = :id_assignatura";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_alumne' => $idAlumne,
            'id_assignatura' => $idAssignatura
        ]);
    }
}