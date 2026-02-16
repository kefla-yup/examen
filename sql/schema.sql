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


INSERT INTO types_besoin (nom, description) VALUES
('Nature', 'Besoins en nature : riz, huile, eau, nourriture...'),
('Matériaux', 'Besoins en matériaux : tôle, clou, bois, ciment...'),
('Argent', 'Besoins financiers en Ariary');


INSERT INTO villes (nom, region, population) VALUES
('Mananjary', 'Vatovavy-Fitovinany', 35000),
('Ikongo', 'Vatovavy-Fitovinany', 22000),
('Nosy Varika', 'Vatovavy-Fitovinany', 18000),
('Farafangana', 'Atsimo-Atsinanana', 40000),
('Vangaindrano', 'Atsimo-Atsinanana', 28000),
('Mahanoro', 'Atsinanana', 30000);


INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES
(1, 1, 'Riz (kg)', 2500.00, 5000),
(1, 1, 'Huile (litre)', 8000.00, 1000),
(1, 2, 'Tôle (feuille)', 45000.00, 500),
(1, 2, 'Clou (kg)', 6000.00, 200),
(1, 3, 'Aide financière (Ar)', 1.00, 50000000);


INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES
(2, 1, 'Riz (kg)', 2500.00, 3000),
(2, 1, 'Eau potable (litre)', 500.00, 10000),
(2, 2, 'Bois (planche)', 15000.00, 300),
(2, 3, 'Aide financière (Ar)', 1.00, 20000000);


INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES
(3, 1, 'Riz (kg)', 2500.00, 2000),
(3, 2, 'Ciment (sac)', 35000.00, 100),
(3, 2, 'Tôle (feuille)', 45000.00, 200);


INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES
(4, 1, 'Riz (kg)', 2500.00, 8000),
(4, 1, 'Huile (litre)', 8000.00, 2000),
(4, 2, 'Tôle (feuille)', 45000.00, 800),
(4, 3, 'Aide financière (Ar)', 1.00, 80000000);


INSERT INTO dons (donateur, type_besoin_id, designation, quantite, date_don) VALUES
('Croix Rouge', 1, 'Riz (kg)', 3000, '2026-02-01'),
('UNICEF', 1, 'Huile (litre)', 500, '2026-02-02'),
('Gouvernement', 2, 'Tôle (feuille)', 300, '2026-02-03'),
('Association Solidarité', 1, 'Riz (kg)', 2000, '2026-02-05'),
('Banque Mondiale', 3, 'Aide financière (Ar)', 30000000, '2026-02-06'),
('Anonyme', 2, 'Clou (kg)', 150, '2026-02-07'),
('ONG Care', 1, 'Eau potable (litre)', 5000, '2026-02-08'),
('Communauté locale', 2, 'Bois (planche)', 100, '2026-02-10');
