<?php

class AssignaturesAlumnes {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllMatricules($idUsuari = null) {
        $sql = "SELECT aa.*, u.nom as nom_complet, ass.nom as nom_assignatura, aa.rebre_notificacions
                FROM Assignatures_Alumnes aa
                JOIN Alumnes a ON aa.id_alumne = a.id_alumne
                JOIN Usuaris u ON a.id_usuari = u.id_usuari
                JOIN Assignatures ass ON aa.id_assignatura = ass.id_assignatura";
        
        if ($idUsuari !== null) {
            $sql .= " WHERE u.id_usuari = :id_usuari";
        }
        
        $sql .= " ORDER BY u.nom, ass.nom";
        
        $stmt = $this->pdo->prepare($sql);
        if ($idUsuari !== null) {
            $stmt->execute(['id_usuari' => $idUsuari]);
        } else {
            $stmt->execute();
        }
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

    public function matricularAlumne($idAlumne, $idAssignatura, $rebreNotificacions = false) {
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
        $sql = "INSERT INTO Assignatures_Alumnes (id_alumne, id_assignatura, rebre_notificacions) 
                VALUES (:id_alumne, :id_assignatura, :rebre_notificacions)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_alumne' => $idAlumne,
            'id_assignatura' => $idAssignatura,
            'rebre_notificacions' => $rebreNotificacions ? 1 : 0
        ]);
    }

    public function toggleNotificacions($idAlumne, $idAssignatura) {
        $sql = "UPDATE Assignatures_Alumnes 
                SET rebre_notificacions = NOT rebre_notificacions 
                WHERE id_alumne = :id_alumne AND id_assignatura = :id_assignatura";
        
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

    public function getAssignaturesByCurs($idCurs) {
        $sql = "SELECT DISTINCT a.*
                FROM Assignatures a
                JOIN Horari h ON a.id_assignatura = h.id_assignatura
                WHERE h.id_curs = :id_curs
                ORDER BY a.nom";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_curs' => $idCurs]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCursByAlumne($idUsuari) {
        $sql = "SELECT c.*
                FROM Cursos c
                JOIN Alumnes a ON c.id_curs = a.id_curs
                WHERE a.id_usuari = :id_usuari";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_usuari' => $idUsuari]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAlumneIdByUserId($userId) {
        $sql = "SELECT id_alumne FROM Alumnes WHERE id_usuari = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id_alumne'] : null;
    }

    public function getMatriculesAlumne($idAlumne) {
        $sql = "SELECT aa.*, a.nom as nom_assignatura, u.nom as nom_complet 
                FROM Assignatures_Alumnes aa
                INNER JOIN Assignatures a ON aa.id_assignatura = a.id_assignatura
                INNER JOIN Alumnes al ON aa.id_alumne = al.id_alumne
                INNER JOIN Usuaris u ON al.id_usuari = u.id_usuari
                WHERE aa.id_alumne = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idAlumne]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAssignaturesDisponibles($idAlumne) {
        $sql = "SELECT a.* 
                FROM Assignatures a
                WHERE a.id_assignatura NOT IN (
                    SELECT id_assignatura 
                    FROM Assignatures_Alumnes 
                    WHERE id_alumne = ?
                )";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idAlumne]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}