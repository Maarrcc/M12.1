<?php
// models/Horari.php
class Horari
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getHorariBase($cursComplet)
    {
        list($cicle, $any) = explode('-', $cursComplet);

        $sql = "SELECT 
                H.id_horari,
                H.hora_inici, 
                H.hora_fi, 
                H.dia,
                C.nom_cicle,
                C.any_academic,
                A.nom_aula AS aula,
                U.nom AS professor, 
                Asg.nom AS assignatura
            FROM Horari H
            JOIN Cursos C ON H.id_curs = C.id_curs
            JOIN Aulas A ON H.id_aula = A.id_aula
            JOIN Assignatures Asg ON H.id_assignatura = Asg.id_assignatura
            JOIN Professors P ON H.id_professor = P.id_professor
            JOIN Usuaris U ON P.id_usuari = U.id_usuari
            WHERE C.nom_cicle = ? AND C.any_academic = ?
            ORDER BY H.hora_inici";
            
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cicle, $any]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCanvis($start, $end, $cursComplet)
    {
        list($cicle, $any) = explode('-', $cursComplet);

        $sql = "SELECT 
                C.*,
                H.dia,
                H.hora_inici,
                H.hora_fi,
                Cu.nom_cicle,
                Cu.any_academic,
                A.nom_aula as aula_original,
                Au.nom_aula as aula_substituta,
                U1.nom as professor_original,
                U2.nom as professor_substitut
            FROM Canvis C
            JOIN Horari H ON C.id_horari = H.id_horari
            JOIN Cursos Cu ON C.id_curs = Cu.id_curs
            JOIN Aulas A ON H.id_aula = A.id_aula
            LEFT JOIN Aulas Au ON C.id_aula_substituta = Au.id_aula
            JOIN Professors P1 ON H.id_professor = P1.id_professor
            JOIN Usuaris U1 ON P1.id_usuari = U1.id_usuari
            LEFT JOIN Professors P2 ON C.id_professor_substitut = P2.id_professor
            LEFT JOIN Usuaris U2 ON P2.id_usuari = U2.id_usuari
            WHERE C.data_canvi BETWEEN ? AND ?
            AND Cu.nom_cicle = ? AND Cu.any_academic = ?
            AND C.estat = 'actiu'
            ORDER BY C.data_canvi, H.hora_inici";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$start, $end, $cicle, $any]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "SELECT h.*, 
                       a.nom AS nom_assignatura,
                       p.nom AS nom_professor,
                       au.nom_aula,
                       c.nom_cicle,
                       c.any_academic
                FROM Horari h
                JOIN Assignatures a ON h.id_assignatura = a.id_assignatura
                JOIN Professors p ON h.id_professor = p.id_professor
                JOIN Aulas au ON h.id_aula = au.id_aula
                JOIN Cursos c ON h.id_curs = c.id_curs
                ORDER BY h.dia, h.hora_inici";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT h.*, 
                       a.nom AS nom_assignatura,
                       p.nom AS nom_professor,
                       au.nom_aula
                FROM Horari h
                JOIN Assignatures a ON h.id_assignatura = a.id_assignatura
                JOIN Professors p ON h.id_professor = p.id_professor
                JOIN Aulas au ON h.id_aula = au.id_aula
                WHERE h.id_horari = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO Horari (id_assignatura, id_professor, id_aula, id_curs, dia, hora_inici, hora_fi) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['id_assignatura'],
            $data['id_professor'],
            $data['id_aula'],
            $data['id_curs'],
            $data['dia'],
            $data['hora_inici'],
            $data['hora_fi']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE Horari 
                SET id_assignatura = ?, 
                    id_professor = ?, 
                    id_aula = ?, 
                    id_curs = ?, 
                    dia = ?, 
                    hora_inici = ?, 
                    hora_fi = ?
                WHERE id_horari = ?";
        
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
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Horari WHERE id_horari = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getAllAssignatures()
    {
        $stmt = $this->pdo->query("SELECT * FROM Assignatures ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProfessors()
    {
        $stmt = $this->pdo->query("SELECT p.*, u.nom FROM Professors p JOIN Usuaris u ON p.id_usuari = u.id_usuari ORDER BY u.nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllAulas()
    {
        $stmt = $this->pdo->query("SELECT * FROM Aulas ORDER BY nom_aula");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCursos()
    {
        $stmt = $this->pdo->query("SELECT * FROM Cursos ORDER BY nom_cicle, any_academic");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllHoraris() {
        $sql = "SELECT h.*, 
                c.nom_cicle, 
                c.any_academic,
                a.nom AS assignatura,
                u.nom as professor,
                au.nom_aula as aula
                FROM Horari h
                JOIN Cursos c ON h.id_curs = c.id_curs
                JOIN Assignatures a ON h.id_assignatura = a.id_assignatura
                JOIN Professors p ON h.id_professor = p.id_professor
                JOIN Usuaris u ON p.id_usuari = u.id_usuari
                JOIN Aulas au ON h.id_aula = au.id_aula
                ORDER BY c.nom_cicle, c.any_academic, h.dia, h.hora_inici";
                
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
