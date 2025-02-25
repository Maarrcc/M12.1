-- Creació de la taula Cursos
CREATE TABLE Cursos (
    id_curs INT AUTO_INCREMENT PRIMARY KEY,
    nom_cicle ENUM('DAW', 'DAM', 'ASIX') NOT NULL,
    any_academic ENUM('Primer', 'Segon') NOT NULL
);

-- Creació de la taula Usuaris
CREATE TABLE Usuaris (
    id_usuari INT AUTO_INCREMENT PRIMARY KEY,
    nom_usuari VARCHAR(100) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasenya VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'alumne', 'professor') NOT NULL
);

-- Creació de la taula Alumnes
CREATE TABLE Alumnes (
    id_alumne INT AUTO_INCREMENT PRIMARY KEY,
    id_curs INT NOT NULL,
    id_usuari INT,
    FOREIGN KEY (id_curs) REFERENCES Cursos(id_curs),
    FOREIGN KEY (id_usuari) REFERENCES Usuaris(id_usuari)
);

-- Creació de la taula Professors
CREATE TABLE Professors (
    id_professor INT AUTO_INCREMENT PRIMARY KEY,
    id_usuari INT,
    FOREIGN KEY (id_usuari) REFERENCES Usuaris(id_usuari)
);

-- Creació de la taula Assignatures
CREATE TABLE Assignatures (
    id_assignatura INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    descripcio TEXT,
    hores INT NOT NULL
);

-- Relació entre Assignatures i Professors
CREATE TABLE Assignatures_Professors (
    id_assignatura INT,
    id_professor INT,
    PRIMARY KEY (id_assignatura, id_professor),
    FOREIGN KEY (id_assignatura) REFERENCES Assignatures(id_assignatura),
    FOREIGN KEY (id_professor) REFERENCES Professors(id_professor)
);

-- Creació de la taula Aulas
CREATE TABLE Aulas (
    id_aula INT AUTO_INCREMENT PRIMARY KEY,
    nom_aula VARCHAR(50) NOT NULL,
    capacitat INT NOT NULL
);

-- Creació de la taula Horari
CREATE TABLE Horari (
    id_horari INT AUTO_INCREMENT PRIMARY KEY,
    id_assignatura INT,
    id_professor INT,
    id_aula INT,
    id_curs INT NOT NULL,
    dia ENUM('Dilluns', 'Dimarts', 'Dimecres', 'Dijous', 'Divendres') NOT NULL,
    hora_inici TIME NOT NULL,
    hora_fi TIME NOT NULL,
    FOREIGN KEY (id_assignatura) REFERENCES Assignatures(id_assignatura),
    FOREIGN KEY (id_professor) REFERENCES Professors(id_professor),
    FOREIGN KEY (id_aula) REFERENCES Aulas(id_aula),
    FOREIGN KEY (id_curs) REFERENCES Cursos(id_curs)
);

-- Creació de la taula Canvis
CREATE TABLE Canvis (
    id_canvi INT AUTO_INCREMENT PRIMARY KEY,
    id_horari INT,
    id_curs INT NOT NULL,
    tipus_canvi ENUM('Absència professor', 'Canvi aula', 'Canvi professor', 'Classe cancelada', 'Altres') NOT NULL,
    data_canvi DATE NOT NULL,
    data_fi DATE,
    id_professor_substitut INT NULL,
    id_aula_substituta INT NULL,
    descripcio_canvi TEXT,
    estat ENUM('actiu', 'inactiu') DEFAULT 'actiu',
    FOREIGN KEY (id_horari) REFERENCES Horari(id_horari),
    FOREIGN KEY (id_curs) REFERENCES Cursos(id_curs),
    FOREIGN KEY (id_professor_substitut) REFERENCES Professors(id_professor),
    FOREIGN KEY (id_aula_substituta) REFERENCES Aulas(id_aula)
);

-- Creació de la taula Assignatures_Alumnes
CREATE TABLE Assignatures_Alumnes (
    id_assignatura INT,
    id_alumne INT,
    PRIMARY KEY (id_assignatura, id_alumne),
    FOREIGN KEY (id_assignatura) REFERENCES Assignatures(id_assignatura),
    FOREIGN KEY (id_alumne) REFERENCES Alumnes(id_alumne)
);

-- Índexs
ALTER TABLE Canvis ADD INDEX idx_data_canvi (data_canvi);
ALTER TABLE Canvis ADD INDEX idx_estat (estat);
ALTER TABLE Canvis ADD INDEX idx_curs (id_curs);
ALTER TABLE Horari ADD INDEX idx_curs (id_curs);
ALTER TABLE Horari ADD INDEX idx_dia_hora (dia, hora_inici);
ALTER TABLE Assignatures_Alumnes ADD INDEX idx_assignatura (id_assignatura);
ALTER TABLE Assignatures_Alumnes ADD INDEX idx_alumne (id_alumne);

-- Inserció dels cursos
INSERT INTO Cursos (nom_cicle, any_academic) VALUES
    ('DAW', 'Primer'),
    ('DAW', 'Segon'),
    ('DAM', 'Primer'),
    ('DAM', 'Segon'),
    ('ASIX', 'Primer'),
    ('ASIX', 'Segon');

-- Inserció d'usuaris
INSERT INTO Usuaris (nom_usuari, nom, email, contrasenya, rol) VALUES
    ('alumne1', 'Alumne 1', 'alumne1@mail.com', SHA2('password1', 256), 'alumne'),
    ('gerard.herencia', 'Gerard Herencia', 'gerard.herencia@centre.com', SHA2('gh123', 256), 'professor'),
    ('xaloc.garay', 'Xaloc Garay', 'xaloc.garay@centre.com', SHA2('xg123', 256), 'professor'),
    ('diego.munoz', 'Diego Muñoz', 'diego.muñoz@centre.com', SHA2('dm123', 256), 'professor'),
    ('mila.machio', 'Mila Machío', 'mila.machio@centre.com', SHA2('mm123', 256), 'professor'),
    ('alex.batan', 'Alex Batán', 'alex.batan@centre.com', SHA2('ab123', 256), 'professor'),
    ('david.lopez', 'David Lopez', 'david.lopez@centre.com', SHA2('dl123', 256), 'professor');

-- Inserció d'alumnes (ara amb id_curs)
INSERT INTO Alumnes (id_curs, id_usuari) VALUES
    (2, 2); -- Alumne1 a DAW Segon

-- Inserció de professors
INSERT INTO Professors (id_usuari) VALUES
    (3), -- Gerard Herencia
    (4), -- Xaloc Garay
    (5), -- Diego Muñoz
    (6), -- Mila Machío
    (7), -- Alex Batán
    (8); -- David Lopez

-- Inserció d'assignatures
INSERT INTO Assignatures (nom, descripcio, hores) VALUES
    ('M12', 'Projecte Final', 8),
    ('M6', 'Desenvolupament Web', 6),
    ('M7', 'Desenvolupament de Aplicacions', 6),
    ('M8', 'Desplegament de Aplicacions', 4),
    ('M9', 'Disseny de Interfícies', 4);

-- Inserció de relacions Assignatures-Professors
INSERT INTO Assignatures_Professors (id_assignatura, id_professor) VALUES
    (1, 1), -- Gerard Herencia - M12
    (1, 2), -- Xaloc Garay - M12
    (2, 3), -- Diego Muñoz - M6
    (3, 4), -- Mila Machío - M7
    (4, 5), -- Alex Batán - M8
    (5, 4), -- Mila Machío - M9
    (1, 6); -- David Lopez - M12

-- Inserció d'aules
INSERT INTO Aulas (nom_aula, capacitat) VALUES
    ('212', 30),
    ('213', 25),
    ('214', 20),
    ('215', 35),
    ('216', 40);

-- Inserció d'horari DAW Segon
INSERT INTO Horari (id_assignatura, id_professor, id_aula, id_curs, dia, hora_inici, hora_fi) VALUES 
-- Dilluns
(1, 1, 1, 2, 'Dilluns', '15:00', '16:00'),
(1, 2, 1, 2, 'Dilluns', '16:00', '17:00'),
(2, 3, 1, 2, 'Dilluns', '17:00', '18:00'),
(3, 4, 1, 2, 'Dilluns', '18:30', '19:30'),
(3, 4, 1, 2, 'Dilluns', '19:30', '20:30'),
-- Dimarts
(1, 2, 1, 2, 'Dimarts', '16:00', '17:00'),
(2, 3, 1, 2, 'Dimarts', '17:00', '18:00'),
(3, 4, 1, 2, 'Dimarts', '18:30', '19:30'),
(5, 4, 1, 2, 'Dimarts', '19:30', '20:30'),
(1, 2, 1, 2, 'Dimarts', '20:30', '21:30'),
-- Dimecres
(4, 5, 1, 2, 'Dimecres', '17:00', '18:00'),
(4, 5, 1, 2, 'Dimecres', '18:30', '19:30'),
(2, 3, 1, 2, 'Dimecres', '19:30', '20:30'),
(1, 6, 1, 2, 'Dimecres', '20:30', '21:30'),
-- Dijous
(1, 2, 1, 2, 'Dijous', '15:00', '16:00'),
(2, 3, 1, 2, 'Dijous', '16:00', '17:00'),
(2, 3, 1, 2, 'Dijous', '17:00', '18:00'),
(1, 2, 1, 2, 'Dijous', '18:30', '19:30'),
(1, 6, 1, 2, 'Dijous', '19:30', '20:30'),
(1, 6, 1, 2, 'Dijous', '20:30', '21:30'),
-- Divendres
(5, 2, 1, 2, 'Divendres', '16:00', '17:00'),
(5, 2, 1, 2, 'Divendres', '17:00', '18:00'),
(3, 4, 1, 2, 'Divendres', '18:30', '19:30'),
(3, 4, 1, 2, 'Divendres', '19:30', '20:30');

-- Inserció d'horari DAM Segon
INSERT INTO Horari (id_assignatura, id_professor, id_aula, id_curs, dia, hora_inici, hora_fi) VALUES
(1, 2, 2, 4, 'Dilluns', '15:00', '16:00'),
(2, 3, 2, 4, 'Dilluns', '16:00', '17:00');

-- Inserció de canvis
INSERT INTO Canvis (id_horari, id_curs, tipus_canvi, data_canvi, data_fi, id_professor_substitut, id_aula_substituta, descripcio_canvi, estat) VALUES
(1, 2, 'Absència professor', '2025-02-04', '2025-02-04', 2, NULL, 'Professor absent, substituït per Xaloc Garay', 'actiu'),
(5, 2, 'Canvi aula', '2025-02-05', '2025-02-05', NULL, 2, 'Canvi a aula 213 per problemes tècnics', 'actiu'),
(10, 2, 'Classe cancelada', '2025-01-30', '2025-01-30', NULL, NULL, 'Classe cancelada per reunió de departament', 'actiu');

-- Inserció d'Assignatures_Alumnes
INSERT INTO Assignatures_Alumnes (id_assignatura, id_alumne) VALUES
    (1, 1); -- Alumne1 inscrit a M12