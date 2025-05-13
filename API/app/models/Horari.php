<?php
class Horari {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        try {
            $sql = "SELECT h.*, 
                        c.nom_cicle, 
                        c.any_academic,
                        a.nom AS assignatura,
                        u.nom as professor,
                        au.nom_aula as aula
                    FROM horari h
                    JOIN cursos c ON h.id_curs = c.id_curs
                    JOIN assignatures a ON h.id_assignatura = a.id_assignatura
                    JOIN professors p ON h.id_professor = p.id_professor
                    JOIN usuaris u ON p.id_usuari = u.id_usuari
                    JOIN aulas au ON h.id_aula = au.id_aula
                    ORDER BY c.nom_cicle, c.any_academic, h.dia, h.hora_inici";
            
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener los horarios: " . $e->getMessage());
        }
    }

    public function getByCurs($cursComplet) {
        try {
            list($cicle, $any) = explode('-', $cursComplet);

            $sql = "SELECT 
                    h.id_horari,
                    h.hora_inici, 
                    h.hora_fi, 
                    h.dia,
                    c.nom_cicle,
                    c.any_academic,
                    au.nom_aula AS aula,
                    u.nom AS professor, 
                    a.nom AS assignatura
                FROM horari h
                JOIN cursos c ON h.id_curs = c.id_curs
                JOIN aulas au ON h.id_aula = au.id_aula
                JOIN assignatures a ON h.id_assignatura = a.id_assignatura
                JOIN professors p ON h.id_professor = p.id_professor
                JOIN usuaris u ON p.id_usuari = u.id_usuari
                WHERE c.nom_cicle = ? 
                AND c.any_academic = ?
                ORDER BY h.dia, h.hora_inici";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$cicle, $any]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el horario del curso: " . $e->getMessage());
        }
    }

    public function getByCursAndDia($cursComplet, $dia) {
        try {
            list($cicle, $any) = explode('-', $cursComplet);
            $sql = "SELECT 
                    h.id_horari,
                    h.hora_inici, 
                    h.hora_fi, 
                    h.dia,
                    c.nom_cicle,
                    c.any_academic,
                    au.nom_aula AS aula,
                    u.nom AS professor, 
                    a.nom AS assignatura,
                    h.id_curs
                FROM horari h
                JOIN cursos c ON h.id_curs = c.id_curs
                JOIN sulas au ON h.id_aula = au.id_aula
                JOIN sssignatures a ON h.id_assignatura = a.id_assignatura
                JOIN professors p ON h.id_professor = p.id_professor
                JOIN usuaris u ON p.id_usuari = u.id_usuari
                WHERE c.nom_cicle = ? 
                AND c.any_academic = ?
                AND h.dia = ?
                ORDER BY h.hora_inici";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$cicle, $any, $dia]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el horario del curso y día: " . $e->getMessage());
        }
    }

    public function getById($id) {
        $sql = "SELECT h.*, 
                       a.nom AS assignatura,
                       u.nom AS professor,
                       au.nom_aula as aula,
                       c.nom_cicle,
                       c.any_academic
                FROM horari h
                JOIN assignatures a ON h.id_assignatura = a.id_assignatura
                JOIN professors p ON h.id_professor = p.id_professor
                JOIN usuaris u ON p.id_usuari = u.id_usuari
                JOIN aulas au ON h.id_aula = au.id_aula
                JOIN cursos c ON h.id_curs = c.id_curs
                WHERE h.id_horari = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO horari (id_assignatura, id_professor, id_aula, id_curs, dia, hora_inici, hora_fi) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['id_assignatura'],
                $data['id_professor'],
                $data['id_aula'],
                $data['id_curs'],
                $data['dia'],
                $data['hora_inici'],
                $data['hora_fi']
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error al crear el horario: " . $e->getMessage());
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE horari 
                SET id_assignatura = ?, 
                    id_professor = ?, 
                    id_aula = ?, 
                    id_curs = ?, 
                    dia = ?, 
                    hora_inici = ?, 
                    hora_fi = ?
                WHERE id_horari = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['id_assignatura'],
                $data['id_professor'],
                $data['id_aula'],
                $data['id_curs'],
                $data['dia'],
                $data['hora_inici'],
                $data['hora_fi'],
                $id
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar el horario: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM horari WHERE id_horari = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar el horario: " . $e->getMessage());
        }
    }
}