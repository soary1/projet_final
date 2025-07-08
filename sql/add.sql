ALTER TABLE banque_type_pret ADD COLUMN assurance DECIMAL(5,2) DEFAULT 0;

CREATE TABLE banque_remboursement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pret INT NOT NULL,
    montant DECIMAL(12,2) NOT NULL,
    date_paiement DATE NOT NULL,
    date_echeance DATE NOT NULL,
    FOREIGN KEY (id_pret) REFERENCES banque_pret(id) ON DELETE CASCADE
);


-- Paiements réguliers pour prêt ID 5
INSERT INTO banque_remboursement (id_pret, montant, date_paiement, date_echeance) VALUES
(5, 1562500.00, '2024-02-05', '2024-02-01'),
(5, 1562500.00, '2024-03-01', '2024-03-01'),
(5, 1562500.00, '2024-04-01', '2024-04-01'),
(5, 1562500.00, '2024-05-01', '2024-05-01');

-- Paiements en retard pour prêt ID 6
INSERT INTO banque_remboursement (id_pret, montant, date_paiement, date_echeance) VALUES
(6, 625000.00, '2024-04-10', '2024-04-01'),
(6, 625000.00, '2024-05-05', '2024-05-01'),
(6, 625000.00, '2024-06-01', '2024-06-01');

-- Paiements pour prêt ID 8
INSERT INTO banque_remboursement (id_pret, montant, date_paiement, date_echeance) VALUES
(8, 1250000.00, '2025-02-01', '2025-02-01'),
(8, 1250000.00, '2025-03-01', '2025-03-01'),
(8, 1250000.00, '2025-04-01', '2025-04-01');

-- Paiements pour prêt ID 10
INSERT INTO banque_remboursement (id_pret, montant, date_paiement, date_echeance) VALUES
(10, 100000.00, '2025-04-01', '2025-04-01'),
(10, 100000.00, '2025-05-01', '2025-05-01'),
(10, 100000.00, '2025-06-01', '2025-06-01');

-- Paiement en retard pour prêt ID 11
INSERT INTO banque_remboursement (id_pret, montant, date_paiement, date_echeance) VALUES
(11, 150000.00, '2025-05-10', '2025-05-01');

-- Paiements pour prêt ID 14
INSERT INTO banque_remboursement (id_pret, montant, date_paiement, date_echeance) VALUES
(14, 900000.00, '2025-08-01', '2025-08-01'),
(14, 900000.00, '2025-09-01', '2025-09-01');

-- Paiement pour prêt ID 15
INSERT INTO banque_remboursement (id_pret, montant, date_paiement, date_echeance) VALUES
(15, 80000.00, '2026-02-01', '2026-02-01');
