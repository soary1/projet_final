-- Prêts 2024
INSERT INTO banque_pret (id_client, id_agent, id_type_pret, montant, date_demande, statut) VALUES
(1, 1, 1, 150000000, '2024-01-10', 'valide'),
(1, 1, 2, 50000000,  '2024-03-20', 'valide'),
(1, 1, 3, 30000000,  '2024-06-05', 'valide');

-- Prêts 2025
INSERT INTO banque_pret (id_client, id_agent, id_type_pret, montant, date_demande, statut) VALUES
(1, 1, 1, 120000000, '2025-01-15', 'valide'),
(1, 1, 2, 30000000,  '2025-02-20', 'valide'),
(1, 1, 3, 10000000,  '2025-03-10', 'valide'),
(1, 1, 4, 15000000,  '2025-04-05', 'valide'),
(1, 1, 3, 7000000,   '2025-05-12', 'valide'),
(1, 1, 2, 20000000,  '2025-06-07', 'valide'),
(1, 1, 1, 90000000,  '2025-07-22', 'valide');

-- Prêts 2026
INSERT INTO banque_pret (id_client, id_agent, id_type_pret, montant, date_demande, statut) VALUES
(1, 1, 3, 8000000,   '2026-01-03', 'valide'),
(1, 1, 4, 9500000,   '2026-02-17', 'valide'),
(1, 1, 1, 180000000, '2026-03-30', 'valide');
/* ====================================================================
   INSERTIONS DE DONNÉES DE TEST
   ==================================================================== */
START TRANSACTION;

/* ------------------------------
   Utilisateurs
   ------------------------------ */
INSERT INTO banque_utilisateur (id, nom, email, mot_de_passe) VALUES
  (4, 'Jean Dupont',      'jean.dupont@example.com',      'password123'),
  (5, 'Claire Bernard',   'claire.bernard@example.com',   'password123'),
  (6, 'Alexandre Girard', 'alex.girard@example.com',      'password123'),
  (7, 'Sophie Agent',     'sophie.agent@example.com',     'password123');


/* ------------------------------
   Clients
   ------------------------------ */
INSERT INTO banque_client (id, id_utilisateur, profession, revenu_mensuel) VALUES
  (2, 4, 'Ingénieur logiciel', 4200.00),
  (3, 5, 'Designer graphique', 3500.00);

/* ------------------------------
   Agents
   ------------------------------ */
INSERT INTO banque_agent (id, id_utilisateur, matricule, date_embauche) VALUES
  (1, 4, 'AG-2023-001', '2023-09-15');

/* ------------------------------
   Types de fonds
   ------------------------------ */
INSERT INTO banque_type_fond (id, nom) VALUES
  (1, 'Capital social'),
  (2, 'Dépôts clients'),
  (3, 'Investissements externes');

/* ------------------------------
   Fonds (mouvements)
   ------------------------------ */
INSERT INTO banque_fond (id, montant, id_type_fond, id_agent, date_ajout) VALUES
  (1, 500000.00, 1, 1, '2024-01-10 09:00:00'),
  (2,  20000.00, 2, 1, '2025-03-18 14:25:00');

/* ------------------------------
   Gammes / Types de prêts
   ------------------------------ */
INSERT INTO banque_type_pret (id, nom, taux_interet, duree_mois) VALUES
  (3, 'Prêt personnel', 3.50,  240);


/* ------------------------------
   Prêts
   ------------------------------ */
INSERT INTO banque_pret (id, id_client, id_agent, id_type_pret, montant, statut, date_demande) VALUES
  (1, 1, 1, 1,   10000.00, 'approuvé',   '2025-05-12 11:00:00'),
  (2, 2, 1, 2,  150000.00, 'en attente', '2025-06-20 15:40:00');

/* ------------------------------
   Rôles applicatifs
   ------------------------------ */
INSERT INTO banque_role (id, nom) VALUES
  (1, 'ADMIN'),
  (2, 'AGENT'),
  (3, 'CLIENT');

/* ------------------------------
   Historique des statuts de prêt
   ------------------------------ */
INSERT INTO banque_historique_pret (
    id, id_pret, ancien_statut, nouveau_statut, date_modif
) VALUES
  (1, 2, 'en attente', 'approuvé', '2025-07-07 10:30:00');

COMMIT;
