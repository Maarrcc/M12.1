<?php

class Canvi {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllHoraris() {
        $sql = "SELECT h.id_horari, h.dia, h.hora_inici, h.hora_fi, 
                       a.nom as assignatura, u.nom as professor, 
                       c.nom_cicle, c.any_academic, au.nom_aula
                FROM Horari h
                INNER JOIN Assignatures a ON h.id_assignatura = a.id_assignatura
                INNER JOIN Professors p ON h.id_professor = p.id_professor
                INNER JOIN Usuaris u ON p.id_usuari = u.id_usuari
                INNER JOIN Cursos c ON h.id_curs = c.id_curs
                INNER JOIN Aulas au ON h.id_aula = au.id_aula
                ORDER BY FIELD(h.dia, 'Dilluns', 'Dimarts', 'Dimecres', 'Dijous', 'Divendres'),
                         h.hora_inici";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProfessors() {
        $sql = "SELECT p.id_professor, u.nom 
                FROM Professors p 
                INNER JOIN Usuaris u ON p.id_usuari = u.id_usuari 
                WHERE u.rol = 'professor'
                ORDER BY u.nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAules() {
        $sql = "SELECT * FROM Aulas ORDER BY nom_aula";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertCanvi($data) {
        try {
            $sql = "INSERT INTO Canvis (id_horari, id_curs, tipus_canvi, data_canvi, 
                    data_fi, id_professor_substitut, id_aula_substituta, descripcio_canvi, estat) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'actiu')";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['id_horari'],
                $data['id_curs'],
                $data['tipus_canvi'],
                $data['data_canvi'],
                $data['data_fi'] ?: null,
                $data['id_professor_substitut'] ?: null,
                $data['id_aula_substituta'] ?: null,
                $data['descripcio_canvi']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error al insertar el cambio: " . $e->getMessage());
        }
    }

    public function getCursos() {
        $sql = "SELECT * FROM Cursos ORDER BY nom_cicle, any_academic";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHorarisByDia($dia) {
        $sql = "SELECT h.id_horari, h.dia, h.hora_inici, h.hora_fi, 
                       a.nom as assignatura, u.nom as professor, 
                       au.nom_aula, c.nom_cicle, c.any_academic,
                       h.id_curs, CONCAT(c.nom_cicle, ' ', c.any_academic) as curs_complet
                FROM Horari h
                INNER JOIN Assignatures a ON h.id_assignatura = a.id_assignatura
                INNER JOIN Professors p ON h.id_professor = p.id_professor
                INNER JOIN Usuaris u ON p.id_usuari = u.id_usuari
                INNER JOIN Cursos c ON h.id_curs = c.id_curs
                INNER JOIN Aulas au ON h.id_aula = au.id_aula
                WHERE h.dia = ?
                ORDER BY c.nom_cicle, c.any_academic, h.hora_inici";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$dia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener los cursos disponibles
    public function getCursosDisponibles() {
        $sql = "SELECT DISTINCT c.id_curs, c.nom_cicle, c.any_academic,
                       CONCAT(c.nom_cicle, ' ', c.any_academic) as nom_complet
                FROM Cursos c
                INNER JOIN Horari h ON c.id_curs = h.id_curs
                ORDER BY c.nom_cicle, c.any_academic";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHorarisByCurs($idCurs) {
        $sql = "SELECT h.id_horari, h.dia, h.hora_inici, h.hora_fi, 
                       a.nom as assignatura, u.nom as professor, 
                       au.nom_aula, c.nom_cicle, c.any_academic,
                       h.id_curs
                FROM Horari h
                INNER JOIN Assignatures a ON h.id_assignatura = a.id_assignatura
                INNER JOIN Professors p ON h.id_professor = p.id_professor
                INNER JOIN Usuaris u ON p.id_usuari = u.id_usuari
                INNER JOIN Cursos c ON h.id_curs = c.id_curs
                INNER JOIN Aulas au ON h.id_aula = au.id_aula
                WHERE h.id_curs = ?
                ORDER BY h.dia, h.hora_inici";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idCurs]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}