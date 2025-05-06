<?php
class Canvi {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCanvis() {
        $sql = "SELECT 
            c.*,
            h.dia,
            h.hora_inici,
            h.hora_fi,
            cu.nom_cicle,
            cu.any_academic,
            a.nom_aula as aula_original,
            au.nom_aula as aula_substituta,
            u1.nom as professor_original,
            u2.nom as professor_substitut,
            asig.nom as assignatura
            FROM Canvis c
            INNER JOIN Horari h ON c.id_horari = h.id_horari
            INNER JOIN Cursos cu ON c.id_curs = cu.id_curs
            INNER JOIN Aulas a ON h.id_aula = a.id_aula
            LEFT JOIN Aulas au ON c.id_aula_substituta = au.id_aula
            INNER JOIN Professors p1 ON h.id_professor = p1.id_professor
            INNER JOIN Usuaris u1 ON p1.id_usuari = u1.id_usuari
            LEFT JOIN Professors p2 ON c.id_professor_substitut = p2.id_professor
            LEFT JOIN Usuaris u2 ON p2.id_usuari = u2.id_usuari
            INNER JOIN Assignatures asig ON h.id_assignatura = asig.id_assignatura
            WHERE c.estat = 'actiu'
            ORDER BY c.data_canvi DESC, h.hora_inici";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener los cambios: " . $e->getMessage());
        }
    }

    public function getCanvisPeriode($cursComplet, $start, $end) {
        list($cicle, $any) = explode('-', $cursComplet);

        $sql = "SELECT 
            c.*,
            h.dia,
            h.hora_inici,
            h.hora_fi,
            cu.nom_cicle,
            cu.any_academic,
            a.nom_aula as aula_original,
            au.nom_aula as aula_substituta,
            u1.nom as professor_original,
            u2.nom as professor_substitut,
            asig.nom as assignatura
            FROM Canvis c
            INNER JOIN Horari h ON c.id_horari = h.id_horari
            INNER JOIN Cursos cu ON c.id_curs = cu.id_curs
            INNER JOIN Aulas a ON h.id_aula = a.id_aula
            LEFT JOIN Aulas au ON c.id_aula_substituta = au.id_aula
            INNER JOIN Professors p1 ON h.id_professor = p1.id_professor
            INNER JOIN Usuaris u1 ON p1.id_usuari = u1.id_usuari
            LEFT JOIN Professors p2 ON c.id_professor_substitut = p2.id_professor
            LEFT JOIN Usuaris u2 ON p2.id_usuari = u2.id_usuari
            INNER JOIN Assignatures asig ON h.id_assignatura = asig.id_assignatura
            WHERE cu.nom_cicle = ?
            AND cu.any_academic = ?
            AND c.data_canvi BETWEEN ? AND ?
            AND c.estat = 'actiu'
            ORDER BY c.data_canvi, h.hora_inici";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$cicle, $any, $start, $end]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener los cambios: " . $e->getMessage());
        }
    }

    public function create($data) {
        try {
            // Obtener el id_curs del horario seleccionado
            $stmt = $this->pdo->prepare("SELECT id_curs FROM Horari WHERE id_horari = ?");
            $stmt->execute([$data['id_horari']]);
            $horari = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$horari) {
                throw new Exception("Horario no encontrado");
            }

            $sql = "INSERT INTO Canvis (id_horari, id_curs, tipus_canvi, data_canvi, 
                    data_fi, id_professor_substitut, id_aula_substituta, descripcio_canvi, estat) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'actiu')";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['id_horari'],
                $horari['id_curs'],
                $data['tipus_canvi'],
                $data['data_canvi'],
                $data['data_fi'] ?? null,
                $data['id_professor_substitut'] ?? null,
                $data['id_aula_substituta'] ?? null,
                $data['descripcio_canvi'] ?? null
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error al crear el cambio: " . $e->getMessage());
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE Canvis 
                    SET tipus_canvi = ?,
                        data_canvi = ?,
                        data_fi = ?,
                        id_professor_substitut = ?,
                        id_aula_substituta = ?,
                        descripcio_canvi = ?
                    WHERE id_canvi = ?";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['tipus_canvi'],
                $data['data_canvi'],
                $data['data_fi'] ?? null,
                $data['id_professor_substitut'] ?? null,
                $data['id_aula_substituta'] ?? null,
                $data['descripcio_canvi'] ?? null,
                $id
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar el cambio: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            // Utilizamos borrado lógico cambiando el estado a 'inactiu'
            $sql = "UPDATE Canvis SET estat = 'inactiu' WHERE id_canvi = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar el cambio: " . $e->getMessage());
        }
    }
}