<?php
require_once __DIR__ . '/../models/Interet.php';

class InteretController {
    public static function getInterets() {
        try {
            $moisDebut = $_GET['mois_debut'] ?? null;
            $anneeDebut = $_GET['annee_debut'] ?? null;
            $moisFin = $_GET['mois_fin'] ?? null;
            $anneeFin = $_GET['annee_fin'] ?? null;

            // Le filtre est facultatif : s’il n’y en a pas, on affiche tout
            $data = Interet::getByPeriode($moisDebut, $anneeDebut, $moisFin, $anneeFin);
            Flight::json($data);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ], 500);
        }
    }

    // public static function interetsParMois() {
    //     $moisDebut = $_GET['mois_debut'] ?? null;
    //     $anneeDebut = $_GET['annee_debut'] ?? null;
    //     $moisFin = $_GET['mois_fin'] ?? null;
    //     $anneeFin = $_GET['annee_fin'] ?? null;

    //     if (!$moisDebut || !$anneeDebut || !$moisFin || !$anneeFin) {
    //         Flight::json(['success' => false, 'message' => 'Paramètres manquants.'], 400);
    //         return;
    //     }

    //     $dateDebut = "$anneeDebut-$moisDebut-01";
    //     $dateFin = date("Y-m-t", strtotime("$anneeFin-$moisFin-01"));

    //     $resultats = Remboursement::getInteretsPrevusEtReels($dateDebut, $dateFin);
    //     Flight::json(['success' => true, 'data' => $resultats]);
    // }

        public static function interetsParMois() {
            $q = Flight::request()->query;
            $dateDebut = $q['annee_debut'] . "-" . $q['mois_debut'] . "-01";
            $dateFin = date("Y-m-t", strtotime($q['annee_fin'] . "-" . $q['mois_fin'] . "-01"));

            $resultats = Remboursement::getInteretsPrevuReelParMois($dateDebut, $dateFin);
            Flight::json(['success' => true, 'data' => $resultats]);
        }

}
