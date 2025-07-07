drop database if exists banque;
-- Créer la base
CREATE DATABASE IF NOT EXISTS banque CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE banque;

-- Table: utilisateur (connexion)
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

-- Table: client
CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    profession VARCHAR(100),
    revenu_mensuel DECIMAL(10,2),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE
);

-- Table: agent
CREATE TABLE agent (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    matricule VARCHAR(50) UNIQUE,
    date_embauche DATE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE
);

-- Table: type_fond
CREATE TABLE type_fond (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- Table: fond
CREATE TABLE fond (
    id INT AUTO_INCREMENT PRIMARY KEY,
    montant DECIMAL(12,2) NOT NULL,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_type_fond INT,
    id_agent INT,
    FOREIGN KEY (id_type_fond) REFERENCES type_fond(id),
    FOREIGN KEY (id_agent) REFERENCES agent(id)
);

-- Table: type_pret
CREATE TABLE type_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    taux_interet DECIMAL(5,2) NOT NULL CHECK (taux_interet >= 0),
    duree_mois INT NOT NULL
);

CREATE TABLE pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT,
    id_agent INT,
    id_type_pret INT,
    montant DECIMAL(12,2) NOT NULL,
    date_demande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(20) DEFAULT 'en attente',
    FOREIGN KEY (id_client) REFERENCES client(id),
    FOREIGN KEY (id_agent) REFERENCES agent(id),
    FOREIGN KEY (id_type_pret) REFERENCES type_pret(id)
);


-- (Optionnel) Table: rôle utilisateur
CREATE TABLE role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE historique_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pret INT,
    ancien_statut VARCHAR(20),
    nouveau_statut VARCHAR(20),
    date_modif DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pret) REFERENCES pret(id) ON DELETE CASCADE
);
