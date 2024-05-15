-- Inserting data into Ekipe (Teams) table
INSERT INTO `wrcFantasy`.`Ekipe` (`idEkipe`, `ime`, `cena`)
VALUES
    (1, 'Hyundai Shell Mobis World Rally Team', 100),
    (2, 'M-Sport Ford World Rally Team', 100),
    (3, 'Toyota Gazoo Racing World Team', 100);

-- Inserting data into Vozniki (Drivers) table
INSERT INTO `wrcFantasy`.`Vozniki` (`idVoznika`, `ime in priimek`, `cena`, `Ekipe_idEkipe`)
VALUES
    (1, 'Lorenzo Bertelli', 1, 3), -- Toyota
    (2, 'Esapekka Lappi', 1, 1),    -- Hyundai
    (3, 'Elfyn Evans', 1, 3),        -- Toyota
    (4, 'Adrien Fourmaux', 1, 2),    -- M-Sport
    (5, 'Takamoto Katsuta', 1, 3),   -- Toyota
    (6, 'Grégoire Munster', 1, 2),   -- M-Sport
    (7, 'Sébastien Ogier', 1, 3),    -- Toyota
    (8, 'Thierry Neuville', 1, 1),   -- Hyundai
    (9, 'Kalle Rovanperä', 1, 3),    -- Toyota
    (10, 'Dani Sordo', 1, 1),        -- Hyundai
    (11, 'Ott Tänak', 1, 1),         -- Hyundai
    (12, 'Andreas Mikkelsen', 1, 1), -- Hyundai
    (13, 'Jourdan Serderidis', 1, 2);-- M-Sport