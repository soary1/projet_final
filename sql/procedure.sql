DELIMITER //

CREATE PROCEDURE synthese_mensuelle(IN d1 DATE, IN d2 DATE)
BEGIN
  WITH RECURSIVE calendrier AS (
      SELECT d1 AS first_day
      UNION ALL
      SELECT DATE_ADD(first_day, INTERVAL 1 MONTH)
      FROM calendrier
      WHERE first_day < d2
  ),
  fonds_mois AS (
      SELECT DATE_FORMAT(date_ajout, '%Y-%m') AS mois,
             SUM(montant) AS fonds
      FROM banque_fond
      GROUP BY mois
  ),
  remb_mois AS (
      SELECT DATE_FORMAT(date_paiement, '%Y-%m') AS mois,
             SUM(montant) AS remboursements
      FROM banque_remboursement
      GROUP BY mois
  ),
  pret_mois AS (
      SELECT DATE_FORMAT(date_demande, '%Y-%m') AS mois,
             SUM(montant) AS empruntes
      FROM banque_pret
      WHERE statut = 'valide'
      GROUP BY mois
  )
  SELECT
      DATE_FORMAT(c.first_day, '%Y-%m') AS mois,
      COALESCE(f.fonds, 0)              AS fonds_du_mois,
      COALESCE(r.remboursements, 0)     AS remboursements_du_mois,
      COALESCE(p.empruntes, 0)          AS empruntes_du_mois,
      SUM(COALESCE(f.fonds, 0)) OVER (ORDER BY c.first_day) AS fonds_cumules,
      SUM(COALESCE(r.remboursements, 0)) OVER (ORDER BY c.first_day) AS remboursements_cumules,
      SUM(COALESCE(p.empruntes, 0)) OVER (ORDER BY c.first_day) AS empruntes_cumules,
      SUM(COALESCE(f.fonds, 0) + COALESCE(r.remboursements, 0) - COALESCE(p.empruntes, 0))
      OVER (ORDER BY c.first_day) AS disponible_cumule
  FROM calendrier c
  LEFT JOIN fonds_mois f ON f.mois = DATE_FORMAT(c.first_day, '%Y-%m')
  LEFT JOIN remb_mois r ON r.mois = DATE_FORMAT(c.first_day, '%Y-%m')
  LEFT JOIN pret_mois p ON p.mois = DATE_FORMAT(c.first_day, '%Y-%m')
  ORDER BY c.first_day;
END //

DELIMITER ;
