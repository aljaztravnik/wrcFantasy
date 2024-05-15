-- Inserting data into Ekipe (Teams) table
INSERT INTO `wrcFantasy`.`Ekipe` (`idEkipe`, `ime`, `cena`)
VALUES
    (1, 'Hyundai Shell Mobis World Rally Team', 35),
    (2, 'M-Sport Ford World Rally Team', 30),
    (3, 'Toyota Gazoo Racing World Team', 25);

-- Inserting data into Vozniki (Drivers) table
INSERT INTO `wrcFantasy`.`Vozniki` (`idVoznika`, `ime in priimek`, `cena`, `Ekipe_idEkipe`)
VALUES
    (1, 'Lorenzo Bertelli', 8, 3), -- Toyota
    (2, 'Esapekka Lappi', 11, 1),    -- Hyundai
    (3, 'Elfyn Evans', 17, 3),        -- Toyota
    (4, 'Adrien Fourmaux', 15, 2),    -- M-Sport
    (5, 'Takamoto Katsuta', 13, 3),   -- Toyota
    (6, 'Grégoire Munster', 8, 2),   -- M-Sport
    (7, 'Sébastien Ogier', 14, 3),    -- Toyota
    (8, 'Thierry Neuville', 18, 1),   -- Hyundai
    (9, 'Kalle Rovanperä', 12, 3),    -- Toyota
    (10, 'Dani Sordo', 9, 1),        -- Hyundai
    (11, 'Ott Tänak', 16, 1),         -- Hyundai
    (12, 'Andreas Mikkelsen', 10, 1), -- Hyundai
    (13, 'Jourdan Serderidis', 8, 2);-- M-Sport
