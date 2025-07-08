<?php

require_once("models/Fond.php");
require_once("models/Pret.php");

class StatistiqueController {
    public static function getDisponibilites() {
        $debut = $_GET['debut'] ?? null;
        $fin = $_GET['fin'] ?? null;

        if (!$debut || !$fin) {
            Flight::json(["error" => "Paramètres manquants"], 400);
            return;
        }

        $moisDebut = new DateTime($debut);
        $moisFin = new DateTime($fin);
        $moisFin->modify('first day of next month');

        $resultat = [];

        while ($moisDebut < $moisFin) {
            $mois = $moisDebut->format("Y-%m");

            $fonds = Fond::getTotalFondsAvant($mois);
            $empruntes = Pret::getMontantEmprunteAvant($mois);
            $remboursements = Pret::getRemboursementsAvant($mois);
            $disponible = $fonds + $remboursements - $empruntes;

            $resultat[] = [
                "mois" => $mois,
                "fonds" => $fonds,
                "montants_empruntes" => $empruntes,
                "remboursements" => $remboursements,
                "disponible" => $disponible
            ];

            $moisDebut->modify('+1 month');
        }

        Flight::json($resultat);
    }
}
