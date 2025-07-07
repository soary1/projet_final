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
}
