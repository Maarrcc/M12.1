<?php

class Alumne {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function assignUsuariToAlumne($id_usuari, $id_curs) {
        try {
            // Verificar que el usuario no sea ya un alumno
            $stmt = $this->pdo->prepare("SELECT id_alumne FROM Alumnes WHERE id_usuari = ?");
            $stmt->execute([$id_usuari]);
            if ($stmt->fetch()) {
                throw new Exception("Aquest usuari ja és un alumne");
            }

            // Insertar nuevo alumno
            $sql = "INSERT INTO Alumnes (id_usuari, id_curs) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_usuari, $id_curs]);
        } catch (PDOException $e) {
            throw new Exception("Error al crear l'alumne: " . $e->getMessage());
        }
    }
}