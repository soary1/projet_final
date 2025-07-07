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
                DATE_FORMAT(p.date_demande, '%Y-%m') AS mois
            FROM banque_pret p
            JOIN banque_client c ON c.id = p.id_client
            JOIN banque_utilisateur u ON u.id = c.id_utilisateur
            JOIN banque_type_pret t ON t.id = p.id_type_pret
            $where
            ORDER BY p.date_demande ASC
        ");
        $stmt->execute($params);
        $prets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultats = [];

        foreach ($prets as $pret) {
            $montant = floatval($pret['montant']);
            $taux_annuel = floatval($pret['taux_interet']);
            $duree = intval($pret['duree_mois']);
            $taux_mensuel = $taux_annuel / 100 / 12;

            // Intérêt simple
            $interet_simple_total = $montant * ($taux_annuel / 100) * ($duree / 12);
            $interet_simple_mensuel = $interet_simple_total / $duree;

            // Intérêt composé
            $interet_compose_total = $montant * pow(1 + $taux_mensuel, $duree) - $montant;
            $interet_compose_mensuel = $interet_compose_total / $duree;

            $resultats[] = [
                'id_pret' => $pret['id_pret'],
                'nom_client' => $pret['nom_client'],
                'montant' => number_format($montant, 2, '.', ''),
                'taux_interet' => number_format($taux_annuel, 2, '.', ''),
                'duree_mois' => $duree,
                'mois' => $pret['mois'],
                'interet_simple_total' => number_format($interet_simple_total, 2, '.', ''),
                'interet_simple_mensuel' => number_format($interet_simple_mensuel, 2, '.', ''),
                'interet_compose_total' => number_format($interet_compose_total, 2, '.', ''),
                'interet_compose_mensuel' => number_format($interet_compose_mensuel, 2, '.', '')
            ];
        }

        return $resultats;
    }
}
