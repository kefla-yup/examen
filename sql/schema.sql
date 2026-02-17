CREATE DATABASE db_s2_ETU003888;
USE db_s2_ETU003888;


CREATE TABLE villes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    region VARCHAR(100) NOT NULL,
    population INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE types_besoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) DEFAULT NULL
);


CREATE TABLE besoins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ville_id INT NOT NULL,
    type_besoin_id INT NOT NULL,
    designation VARCHAR(150) NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_ville (ville_id),
    INDEX idx_type (type_besoin_id),
    CONSTRAINT fk_besoins_villes FOREIGN KEY (ville_id) REFERENCES villes(id) ON DELETE CASCADE,
    CONSTRAINT fk_besoins_types FOREIGN KEY (type_besoin_id) REFERENCES types_besoin(id) ON DELETE CASCADE
);


CREATE TABLE dons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    donateur VARCHAR(150) DEFAULT 'Anonyme',
    type_besoin_id INT NOT NULL,
    designation VARCHAR(150) NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    date_don DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_type_don (type_besoin_id),
    INDEX idx_date (date_don),
    CONSTRAINT fk_dons_types FOREIGN KEY (type_besoin_id) REFERENCES types_besoin(id) ON DELETE CASCADE
);


CREATE TABLE dispatches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    don_id INT NOT NULL,
    ville_id INT NOT NULL,
    besoin_id INT NOT NULL,
    quantite_attribuee INT NOT NULL,
    date_dispatch TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_don (don_id),
    INDEX idx_ville_dispatch (ville_id),
    INDEX idx_besoin_dispatch (besoin_id),
    CONSTRAINT fk_dispatches_dons FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE CASCADE,
    CONSTRAINT fk_dispatches_villes FOREIGN KEY (ville_id) REFERENCES villes(id) ON DELETE CASCADE,
    CONSTRAINT fk_dispatches_besoins FOREIGN KEY (besoin_id) REFERENCES besoins(id) ON DELETE CASCADE
);


CREATE TABLE achats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    besoin_id INT NOT NULL,
    ville_id INT NOT NULL,
    quantite INT NOT NULL,
    montant_ht DECIMAL(15,2) NOT NULL,
    frais_pourcent DECIMAL(5,2) NOT NULL DEFAULT 10.00,
    montant_ttc DECIMAL(15,2) NOT NULL,
    date_achat DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_besoin_achat (besoin_id),
    INDEX idx_ville_achat (ville_id),
    CONSTRAINT fk_achats_besoins FOREIGN KEY (besoin_id) REFERENCES besoins(id) ON DELETE CASCADE,
    CONSTRAINT fk_achats_villes FOREIGN KEY (ville_id) REFERENCES villes(id) ON DELETE CASCADE
);


INSERT INTO types_besoin (nom, description) VALUES
('Nature', 'Besoins en nature : riz, huile, eau, nourriture...'),
('Matériaux', 'Besoins en matériaux : tôle, clou, bois, ciment...'),
('Argent', 'Besoins financiers en Ariary');


INSERT INTO villes (nom, region, population) VALUES
('Toamasina', 'Atsinanana', 55000),
('Mananjary', 'Vatovavy-Fitovinany', 35000),
('Farafangana', 'Atsimo-Atsinanana', 40000),
('Nosy Be', 'Diana', 45000),
('Morondava', 'Menabe', 38000);


-- Toamasina (id=1)
INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES
(1, 1, 'Riz (kg)', 3000.00, 800),
(1, 1, 'Eau (L)', 1000.00, 1500),
(1, 2, 'Tôle', 25000.00, 120),
(1, 2, 'Bâche', 15000.00, 200),
(1, 3, 'Argent', 1.00, 12000000),
(1, 2, 'Groupe', 6750000.00, 3);


-- Mananjary (id=2)
INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES
(2, 1, 'Riz (kg)', 3000.00, 500),
(2, 1, 'Huile (L)', 6000.00, 120),
(2, 2, 'Tôle', 25000.00, 80),
(2, 2, 'Clous (kg)', 8000.00, 60),
(2, 3, 'Argent', 1.00, 6000000);


-- Farafangana (id=3)
INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES
(3, 1, 'Riz (kg)', 3000.00, 600),
(3, 1, 'Eau (L)', 1000.00, 1000),
(3, 2, 'Bâche', 15000.00, 150),
(3, 2, 'Bois', 10000.00, 100),
(3, 3, 'Argent', 1.00, 8000000);


-- Nosy Be (id=4)
INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES
(4, 1, 'Riz (kg)', 3000.00, 300),
(4, 1, 'Haricots', 4000.00, 200),
(4, 2, 'Tôle', 25000.00, 40),
(4, 2, 'Clous (kg)', 8000.00, 30),
(4, 3, 'Argent', 1.00, 4000000);


-- Morondava (id=5)
INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES
(5, 1, 'Riz (kg)', 3000.00, 700),
(5, 1, 'Eau (L)', 1000.00, 1200),
(5, 2, 'Bâche', 15000.00, 180),
(5, 2, 'Bois', 10000.00, 150),
(5, 3, 'Argent', 1.00, 10000000);


INSERT INTO dons (donateur, type_besoin_id, designation, quantite, date_don) VALUES
('Anonyme', 3, 'Argent', 5000000, '2026-02-16'),
('Anonyme', 3, 'Argent', 3000000, '2026-02-16'),
('Anonyme', 3, 'Argent', 4000000, '2026-02-17'),
('Anonyme', 3, 'Argent', 1500000, '2026-02-17'),
('Anonyme', 3, 'Argent', 6000000, '2026-02-17'),
('Anonyme', 1, 'Riz (kg)', 400, '2026-02-16'),
('Anonyme', 1, 'Eau (L)', 600, '2026-02-16'),
('Anonyme', 2, 'Tôle', 50, '2026-02-17'),
('Anonyme', 2, 'Bâche', 70, '2026-02-17'),
('Anonyme', 1, 'Haricots', 100, '2026-02-17'),
('Anonyme', 1, 'Riz (kg)', 2000, '2026-02-18'),
('Anonyme', 2, 'Tôle', 300, '2026-02-18'),
('Anonyme', 1, 'Eau (L)', 5000, '2026-02-18'),
('Anonyme', 3, 'Argent', 20000000, '2026-02-19'),
('Anonyme', 2, 'Bâche', 500, '2026-02-19'),
('Anonyme', 1, 'Haricots', 88, '2026-02-17');

