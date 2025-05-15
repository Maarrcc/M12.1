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
                INNER JOIN assignatures a ON h.id_assignatura = a.id_assignatura
                INNER JOIN professors p ON h.id_professor = p.id_professor
                INNER JOIN usuaris u ON p.id_usuari = u.id_usuari
                INNER JOIN cursos c ON h.id_curs = c.id_curs
                INNER JOIN aulas au ON h.id_aula = au.id_aula
                ORDER BY FIELD(h.dia, 'Dilluns', 'Dimarts', 'Dimecres', 'Dijous', 'Divendres'),
                         h.hora_inici";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProfessors() {
        $sql = "SELECT p.id_professor, u.nom 
                FROM professors p 
                INNER JOIN usuaris u ON p.id_usuari = u.id_usuari 
                WHERE u.rol = 'professor'
                ORDER BY u.nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAules() {
        $sql = "SELECT * FROM aulas ORDER BY nom_aula";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertCanvi($data) {
        try {
            $sql = "INSERT INTO canvis (id_horari, id_curs, tipus_canvi, data_canvi, 
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
        $sql = "SELECT * FROM cursos ORDER BY nom_cicle, any_academic";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHorarisByDia($dia) {
        $sql = "SELECT h.id_horari, h.dia, h.hora_inici, h.hora_fi, 
                       a.nom as assignatura, u.nom as professor, 
                       au.nom_aula, c.nom_cicle, c.any_academic,
                       h.id_curs, CONCAT(c.nom_cicle, ' ', c.any_academic) as curs_complet
                FROM horari h
                INNER JOIN assignatures a ON h.id_assignatura = a.id_assignatura
                INNER JOIN professors p ON h.id_professor = p.id_professor
                INNER JOIN usuaris u ON p.id_usuari = u.id_usuari
                INNER JOIN cursos c ON h.id_curs = c.id_curs
                INNER JOIN aulas au ON h.id_aula = au.id_aula
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
                FROM cursos c
                INNER JOIN horari h ON c.id_curs = h.id_curs
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
                FROM horari h
                INNER JOIN assignatures a ON h.id_assignatura = a.id_assignatura
                INNER JOIN professors p ON h.id_professor = p.id_professor
                INNER JOIN usuaris u ON p.id_usuari = u.id_usuari
                INNER JOIN cursos c ON h.id_curs = c.id_curs
                INNER JOIN aulas au ON h.id_aula = au.id_aula
                WHERE h.id_curs = ?
                ORDER BY h.dia, h.hora_inici";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idCurs]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDetallesCambio($idHorari, $idAulaSubstituta = null, $idProfessorSubstitut = null) {
        $sql = "SELECT h.*, 
            a.nom as assignatura,
            up.nom as professor,
            ups.nom as professor_substitut,
            au.nom_aula as aula_original,
            aus.nom_aula as aula_substituta,
            CONCAT(c.nom_cicle, ' ', c.any_academic) as curs,
            GROUP_CONCAT(DISTINCT ua.email) as alumnes_emails
            FROM horari h
            JOIN assignatures a ON h.id_assignatura = a.id_assignatura
            JOIN professors p ON h.id_professor = p.id_professor
            JOIN usuaris up ON p.id_usuari = up.id_usuari
            LEFT JOIN professors ps ON ps.id_professor = ?
            LEFT JOIN usuaris ups ON ps.id_usuari = ups.id_usuari
            JOIN aulas au ON h.id_aula = au.id_aula
            LEFT JOIN aulas aus ON aus.id_aula = ?
            JOIN cursos c ON h.id_curs = c.id_curs
            LEFT JOIN assignatures_alumnes aa ON a.id_assignatura = aa.id_assignatura 
                AND aa.rebre_notificacions = 1
            LEFT JOIN alumnes al ON aa.id_alumne = al.id_alumne
            LEFT JOIN usuaris ua ON al.id_usuari = ua.id_usuari
            WHERE h.id_horari = ?
            GROUP BY h.id_horari";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idProfessorSubstitut, $idAulaSubstituta, $idHorari]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllCanvis() {
        $sql = "SELECT c.*, 
                CONCAT(cu.nom_cicle, ' ', cu.any_academic) as curs,
                h.dia, h.hora_inici, h.hora_fi
                FROM canvis c
                INNER JOIN horari h ON c.id_horari = h.id_horari
                INNER JOIN cursos cu ON c.id_curs = cu.id_curs
                ORDER BY c.data_canvi DESC, h.hora_inici";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM canvis WHERE id_canvi = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar el canvi: " . $e->getMessage());
        }
    }
}