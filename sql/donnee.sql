USE banque;

-- 1. Utilisateurs (2 agents + 2 clients)
INSERT INTO banque_utilisateur (nom, email, mot_de_passe) VALUES
('Agent Rabe', 'rabe.agent@banque.mg', '1234'),
('Agent Koto', 'koto.agent@banque.mg', '1234'),
('Client Hery', 'hery.client@banque.mg', '1234'),
('Client Vola', 'vola.client@banque.mg', '1234');

-- 2. Agents
INSERT INTO banque_agent (id_utilisateur, matricule, date_embauche) VALUES
(1, 'AGT001', '2020-01-15'),
(2, 'AGT002', '2021-06-10');

-- 3. Clients
INSERT INTO banque_client (id_utilisateur, profession, revenu_mensuel) VALUES
(3, 'Infirmier', 800000.00),
(4, 'Développeur', 1500000.00);

-- 4. Types de fonds
INSERT INTO banque_type_fond (nom) VALUES
('Fonds de roulement'),
('Fonds d’investissement');

-- 5. Fonds
INSERT INTO banque_fond (montant, id_type_fond, id_agent) VALUES
(50000000.00, 1, 1),
(30000000.00, 2, 2);

-- 6. Types de prêts
INSERT INTO banque_type_pret (nom, taux_interet, duree_mois) VALUES
('Crédit consommation', 5.5, 12),
('Crédit immobilier', 7.2, 60);

-- 7. Prêts (statut = en attente pour tests)
INSERT INTO banque_pret (id_client, id_agent, id_type_pret, montant) VALUES
(1, 1, 1, 2000000.00), -- Client Hery demande 2M
(2, 2, 2, 10000000.00); -- Client Vola demande 10M

-- 8. Rôles (si nécessaire plus tard)
INSERT INTO banque_role (nom) VALUES
('client'),
('agent'),
('admin');
