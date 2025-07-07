-- Créer la base
CREATE DATABASE banque;
\c banque;

-- Table: utilisateur (compte pour login)
CREATE TABLE utilisateur (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

-- Table: client (lié à utilisateur)
CREATE TABLE client (
    id SERIAL PRIMARY KEY,
    id_utilisateur INT REFERENCES utilisateur(id),
    profession VARCHAR(100),
    revenu_mensuel NUMERIC(10,2)
);

-- Table: agent (lié à utilisateur)
CREATE TABLE agent (
    id SERIAL PRIMARY KEY,
    id_utilisateur INT REFERENCES utilisateur(id),
    matricule VARCHAR(50) UNIQUE,
    date_embauche DATE
);

-- Table: type_fond (origine du fond)
CREATE TABLE type_fond (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- Table: fond (fonds dans l’établissement financier)
CREATE TABLE fond (
    id SERIAL PRIMARY KEY,
    montant NUMERIC(12,2) NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_type_fond INT REFERENCES type_fond(id),
    id_agent INT REFERENCES agent(id)
);

-- Table: type_pret (ex: immobilier, conso...) avec taux d’intérêt
CREATE TABLE type_pret (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    taux_interet NUMERIC(5,2) NOT NULL CHECK (taux_interet >= 0)
);

-- Table: prêt (lié à client, agent, type_pret)
CREATE TABLE pret (
    id SERIAL PRIMARY KEY,
    id_client INT REFERENCES client(id),
    id_agent INT REFERENCES agent(id),
    id_type_pret INT REFERENCES type_pret(id),
    montant NUMERIC(12,2) NOT NULL,
    duree_mois INT NOT NULL,
    date_demande DATE DEFAULT CURRENT_DATE,
    statut VARCHAR(20) DEFAULT 'en attente' -- ou 'accepté', 'refusé'
);
