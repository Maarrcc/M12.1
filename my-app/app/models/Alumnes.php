<?php

class Alumne {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function countCursosAlumne($id_usuari) {
        $sql = "SELECT COUNT(*) FROM alumnes WHERE id_usuari = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuari]);
        return $stmt->fetchColumn();
    }

    public function assignUsuariToAlumne($id_usuari, $id_curs) {
        try {
            // Verificar que el usuario existe y tenga el rol correcto
            $stmt = $this->pdo->prepare("SELECT rol FROM usuaris WHERE id_usuari = ?");
            $stmt->execute([$id_usuari]);
            $usuari = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$usuari) {
                throw new Exception("L'usuari no existeix");
            }
            
            if ($usuari['rol'] !== 'alumne') {
                throw new Exception("L'usuari ha de tenir el rol d'alumne");
            }

            // Verificar el número de cursos asignados
            $numCursos = $this->countCursosAlumne($id_usuari);
            if ($numCursos >= 2) {
                throw new Exception("L'alumne ja està assignat al màxim de cursos permesos (2)");
            }

            // Verificar que no esté ya asignado a este curso específico
            $stmt = $this->pdo->prepare("SELECT id_alumne FROM alumnes WHERE id_usuari = ? AND id_curs = ?");
            $stmt->execute([$id_usuari, $id_curs]);
            if ($stmt->fetch()) {
                throw new Exception("L'alumne ja està assignat a aquest curs");
            }

            // Verificar que el curso exista
            $stmt = $this->pdo->prepare("SELECT id_curs FROM cursos WHERE id_curs = ?");
            $stmt->execute([$id_curs]);
            if (!$stmt->fetch()) {
                throw new Exception("El curs seleccionat no existeix");
            }

            // Insertar nuevo alumno
            $this->pdo->beginTransaction();
            
            $sql = "INSERT INTO alumnes (id_usuari, id_curs) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$id_usuari, $id_curs]);
            
            if (!$result) {
                throw new Exception("Error al inserir l'alumne");
            }

            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Error al crear l'alumne: " . $e->getMessage());
        }
    }
}