<?php
require_once __DIR__ . '/../db.php';

class Interet {
    public static function getByPeriode($moisDebut = null, $anneeDebut = null, $moisFin = null, $anneeFin = null) {
        $db = getDB();

        $where = '';
        $params = [];

        if ($moisDebut && $anneeDebut && $moisFin && $anneeFin) {
            $dateDebut = "$anneeDebut-$moisDebut-01";
            $dateFin = date("Y-m-t", strtotime("$anneeFin-$moisFin-01"));
            $where = "WHERE p.date_demande BETWEEN ? AND ?";
            $params = [$dateDebut, $dateFin];
        }

        $stmt = $db->prepare("
            SELECT 
                p.id AS id_pret,
                u.nom AS nom_client,
                p.montant,
                t.taux_interet,
                t.duree_mois,
                DATE_FORMAT(p.date_demande, '%Y-%m') AS mois,
                ROUND(p.montant * (t.taux_interet / 100), 2) AS interet_total,
                ROUND(CASE WHEN t.duree_mois > 0 THEN (p.montant * (t.taux_interet / 100)) / t.duree_mois ELSE 0 END, 2) AS interet_mensuel
            FROM banque_pret p
            JOIN banque_client c ON c.id = p.id_client
            JOIN banque_utilisateur u ON u.id = c.id_utilisateur
            JOIN banque_type_pret t ON t.id = p.id_type_pret
            $where
            ORDER BY p.date_demande ASC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
