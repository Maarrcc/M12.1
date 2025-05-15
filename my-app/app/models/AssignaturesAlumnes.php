<?php

class AssignaturesAlumnes {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllMatricules($idUsuari = null) {
        $sql = "SELECT aa.*, u.nom as nom_complet, ass.nom as nom_assignatura, aa.rebre_notificacions
                FROM assignatures_alumnes aa
                JOIN alumnes a ON aa.id_alumne = a.id_alumne
                JOIN usuaris u ON a.id_usuari = u.id_usuari
                JOIN assignatures ass ON aa.id_assignatura = ass.id_assignatura";
        
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
                FROM alumnes a
                JOIN usuaris u ON a.id_usuari = u.id_usuari
                ORDER BY u.nom";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllAssignatures() {
        $sql = "SELECT * FROM assignatures ORDER BY nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function matricularAlumne($idAlumne, $idAssignatura, $rebreNotificacions = false) {
        // Verificar si ya existe la matrícula
        $sql = "SELECT COUNT(*) FROM assignatures_alumnes 
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
        $sql = "INSERT INTO assignatures_alumnes (id_alumne, id_assignatura, rebre_notificacions) 
                VALUES (:id_alumne, :id_assignatura, :rebre_notificacions)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_alumne' => $idAlumne,
            'id_assignatura' => $idAssignatura,
            'rebre_notificacions' => $rebreNotificacions ? 1 : 0
        ]);
    }

    public function toggleNotificacions($idAlumne, $idAssignatura) {
        $sql = "UPDATE assignatures_alumnes 
                SET rebre_notificacions = NOT rebre_notificacions 
                WHERE id_alumne = :id_alumne AND id_assignatura = :id_assignatura";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_alumne' => $idAlumne,
            'id_assignatura' => $idAssignatura
        ]);
    }

    public function deleteMatricula($idAlumne, $idAssignatura) {
        $sql = "DELETE FROM assignatures_alumnes 
                WHERE id_alumne = :id_alumne AND id_assignatura = :id_assignatura";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_alumne' => $idAlumne,
            'id_assignatura' => $idAssignatura
        ]);
    }

    public function getAssignaturesByCurs($idCurs) {
        $sql = "SELECT DISTINCT a.*
                FROM assignatures a
                JOIN horari h ON a.id_assignatura = h.id_assignatura
                WHERE h.id_curs = :id_curs
                ORDER BY a.nom";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_curs' => $idCurs]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCursByAlumne($idUsuari) {
        $sql = "SELECT c.*
                FROM cursos c
                JOIN alumnes a ON c.id_curs = a.id_curs
                WHERE a.id_usuari = :id_usuari";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_usuari' => $idUsuari]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAlumneIdByUserId($idUsuari) {
        $sql = "SELECT id_alumne FROM alumnes WHERE id_usuari = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idUsuari]);
        return $stmt->fetchColumn();
    }

    public function getMatriculesAlumne($idAlumne) {
        $sql = "SELECT aa.*, a.nom as nom_assignatura, u.nom as nom_complet 
                FROM assignatures_alumnes aa
                INNER JOIN assignatures a ON aa.id_assignatura = a.id_assignatura
                INNER JOIN alumnes al ON aa.id_alumne = al.id_alumne
                INNER JOIN usuaris u ON al.id_usuari = u.id_usuari
                WHERE aa.id_alumne = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idAlumne]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAssignaturesDisponibles($idAlumne) {
        $sql = "SELECT a.* 
                FROM assignatures a
                WHERE a.id_assignatura NOT IN (
                    SELECT id_assignatura 
                    FROM assignatures_alumnes 
                    WHERE id_alumne = ?
                )";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idAlumne]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}