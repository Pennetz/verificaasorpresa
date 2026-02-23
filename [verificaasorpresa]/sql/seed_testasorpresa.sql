USE TestASorpresa;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE Catalogo;
TRUNCATE TABLE Pezzi;
TRUNCATE TABLE Fornitori;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO Fornitori (Fid, Fnome, indirizzo) VALUES
    (1, 'Acme', 'Via Roma 1'),
    (2, 'BetaSupply', 'Via Milano 22'),
    (3, 'GammaTools', 'Via Torino 9'),
    (4, 'RossoOnly Srl', 'Via Napoli 7'),
    (5, 'DeltaParts', 'Via Firenze 15'),
    (6, 'VerdeRosso Spa', 'Via Bologna 31');

INSERT INTO Pezzi (Pid, Pnome, colore) VALUES
    (1, 'Bullone', 'rosso'),
    (2, 'Vite', 'verde'),
    (3, 'Dado', 'rosso'),
    (4, 'Rondella', 'blu'),
    (5, 'Ingranaggio', 'rosso'),
    (6, 'Molla', 'verde'),
    (7, 'Cuscinetto', 'nero');

INSERT INTO Catalogo (Fid, Pid, costo) VALUES
    (1, 1, 12),
    (1, 2, 8),
    (1, 3, 10),
    (1, 4, 11),
    (1, 5, 15),
    (1, 6, 9),
    (1, 7, 30),

    (2, 1, 10),
    (2, 2, 8),
    (2, 3, 7),
    (2, 4, 6),

    (3, 1, 11),
    (3, 3, 8),
    (3, 5, 14),

    (4, 1, 9),
    (4, 3, 7),
    (4, 5, 13),

    (5, 1, 13),
    (5, 2, 7),
    (5, 3, 6),
    (5, 4, 5),
    (5, 5, 12),
    (5, 6, 8),

    (6, 1, 14),
    (6, 2, 10),
    (6, 6, 12);
