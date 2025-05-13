<?php

class Professor {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllProfessors() {
        $sql = "SELECT p.id_professor, u.nom, u.email 
                FROM professors p 
                INNER JOIN usuaris u ON p.id_usuari = u.id_usuari
                WHERE u.rol = 'professor'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllAssignatures() {
        $sql = "SELECT * FROM assignatures";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignProfessorToAssignatura($id_professor, $id_assignatura) {
        try {
            $sql = "SELECT * FROM assignatures_Professors 
                    WHERE id_professor = ? AND id_assignatura = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_professor, $id_assignatura]);
            
            if ($stmt->rowCount() > 0) {
                throw new Exception("El profesor ya está asignado a esta asignatura");
            }

            $sql = "INSERT INTO assignatures_Professors (id_professor, id_assignatura) 
                    VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_professor, $id_assignatura]);
        } catch (PDOException $e) {
            throw new Exception("Error al asignar el profesor: " . $e->getMessage());
        }
    }

    public function assignUsuariToProfessor($id_usuari) {
        try {
            // Verificar que el usuario no sea ya un profesor
            $stmt = $this->pdo->prepare("SELECT id_professor FROM professors WHERE id_usuari = ?");
            $stmt->execute([$id_usuari]);
            if ($stmt->fetch()) {
                throw new Exception("Aquest usuari ja és un professor");
            }

            // Insertar nuevo profesor
            $sql = "INSERT INTO professors (id_usuari) VALUES (?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_usuari]);
        } catch (PDOException $e) {
            throw new Exception("Error al crear el professor: " . $e->getMessage());
        }
    }
}