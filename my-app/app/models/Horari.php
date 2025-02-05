<?php
// models/Horari.php
class Horari
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getHorariBase($cursComplet) {
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
            WHERE C.nom_cicle = ? AND C.any_academic = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cicle, $any]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCanvis($start, $end, $cursComplet)
    {
        list($cicle, $any) = explode('-', $cursComplet);
        
        $sql = "SELECT 
                C.id_horari,
                C.tipus_canvi,
                C.descripcio_canvi,
                C.data_canvi,
                Cu.nom_cicle,
                Cu.any_academic
            FROM Canvis C
            JOIN Cursos Cu ON C.id_curs = Cu.id_curs
            WHERE C.data_canvi BETWEEN ? AND ?
            AND Cu.nom_cicle = ? AND Cu.any_academic = ?
            AND C.estat = 'actiu'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$start, $end, $cicle, $any]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
