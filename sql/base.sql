DROP DATABASE IF EXISTS banque;
CREATE DATABASE banque CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE banque;

CREATE TABLE banque_utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

CREATE TABLE banque_client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    profession VARCHAR(100),
    revenu_mensuel DECIMAL(10,2),
    FOREIGN KEY (id_utilisateur) REFERENCES banque_utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE banque_agent (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    matricule VARCHAR(50) UNIQUE,
    date_embauche DATE,
    FOREIGN KEY (id_utilisateur) REFERENCES banque_utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE banque_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    niveau_acces VARCHAR(50) DEFAULT 'standard',
    date_creation DATE DEFAULT (CURRENT_DATE),
    FOREIGN KEY (id_utilisateur) REFERENCES banque_utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE banque_type_fond (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE banque_fond (
    id INT AUTO_INCREMENT PRIMARY KEY,
    montant DECIMAL(12,2) NOT NULL,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_type_fond INT,
    id_agent INT,
    FOREIGN KEY (id_type_fond) REFERENCES banque_type_fond(id),
    FOREIGN KEY (id_agent) REFERENCES banque_agent(id)
);

CREATE TABLE banque_type_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    taux_interet DECIMAL(5,2) NOT NULL CHECK (taux_interet >= 0),
    duree_mois INT NOT NULL
);

CREATE TABLE banque_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT,
    id_agent INT,
    id_type_pret INT,
    montant DECIMAL(12,2) NOT NULL,
    date_demande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(20) DEFAULT 'en attente',
    FOREIGN KEY (id_client) REFERENCES banque_client(id),
    FOREIGN KEY (id_agent) REFERENCES banque_agent(id),
    FOREIGN KEY (id_type_pret) REFERENCES banque_type_pret(id)
);

CREATE TABLE banque_role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE banque_historique_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pret INT,
    ancien_statut VARCHAR(20),
    nouveau_statut VARCHAR(20),
    date_modif DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pret) REFERENCES banque_pret(id) ON DELETE CASCADE
);

-- Insertion utilisateur
INSERT INTO banque_utilisateur (nom, email, mot_de_passe) VALUES 
('Jean Martin', 'agent@banque.com', 'agent123'),       -- id = 1
('Marie Dubois', 'client@banque.com', 'client123');    -- id = 2

-- Agent
INSERT INTO banque_agent (id_utilisateur, matricule, date_embauche) VALUES 
(1, 'AGT001', '2023-01-15');   -- id = 1

-- Client
INSERT INTO banque_client (id_utilisateur, profession, revenu_mensuel) VALUES 
(2, 'Ingénieur', 4500.00);    -- id = 1

-- Types de prêt
INSERT INTO banque_type_pret (nom, taux_interet, duree_mois) VALUES
('Prêt immobilier', 3.50, 240),   -- id = 1
('Prêt automobile', 4.20, 84),    -- id = 2
('Prêt personnel', 6.80, 60),     -- id = 3
('Crédit travaux', 5.10, 120);    -- id = 4

-- Types de fond
INSERT INTO banque_type_fond (nom) VALUES
('Fonds de garantie'),        -- id = 1
('Réserves légales'),         -- id = 2
('Capital social');           -- id = 3

-- Rôles
INSERT INTO banque_role (nom) VALUES
('Client'),
('Agent'),
('Administrateur');

-- Prêts
INSERT INTO banque_pret (id_client, id_agent, id_type_pret, montant, date_demande, statut) VALUES
(1, 1, 1, 100000000, '2025-07-01 10:00:00', 'en attente'), -- id = 1
(1, 1, 2, 25000000,  '2025-07-02 11:00:00', 'en attente'), -- id = 2
(1, 1, 3, 8000000,   '2025-07-03 12:00:00', 'valide'),     -- id = 3
(1, 1, 4, 12000000,  '2025-07-04 13:00:00', 'refuse');     -- id = 4

-- Historique de validation / refus
INSERT INTO banque_historique_pret (id_pret, ancien_statut, nouveau_statut) VALUES
(3, 'en attente', 'valide'),
(4, 'en attente', 'refuse');

-- Fonds
INSERT INTO banque_fond (montant, id_type_fond, id_agent) VALUES
(50000000.00, 1, 1),
(20000000.00, 2, 1),
(75000000.00, 3, 1);
