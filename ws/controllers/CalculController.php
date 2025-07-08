<?php
require_once 'models/CalculModel.php';

class CalculController {

    /**
     * Simulation complète simple et composée
     */
    public function simulate() {
        $montant = $_POST['montant'] ?? null;
        $taux = $_POST['taux'] ?? null;
        $duree = $_POST['duree'] ?? null;
        $assurance = $_POST['assurance'] ?? 0;

        if (!$montant || !$taux || !$duree) {
            Flight::json(['success' => false, 'message' => 'Paramètres manquants']);
            return;
        }

        $montant = floatval($montant);
        $taux = floatval($taux);
        $duree = intval($duree);
        $assurance = floatval($assurance);

        $simple = [
            'interet_total' => CalculModel::interetSimple($montant, $taux, $duree),
            'mensualite' => CalculModel::mensualiteSimple($montant, $taux, $duree),
            'total' => CalculModel::montantTotalSimple($montant, $taux, $duree)
        ];

        $compose = [
            'interet_total' => CalculModel::interetCompose($montant, $taux, $duree),
            'mensualite' => CalculModel::mensualiteComposee($montant, $taux, $duree),
            'total' => CalculModel::montantTotalCompose($montant, $taux, $duree)
        ];

        $assurance_total = CalculModel::assuranceTotal($montant, $assurance, $duree);

        Flight::json([
            'success' => true,
            'data' => [
                'simple' => $simple,
                'compose' => $compose,
                'assurance_total' => $assurance_total
            ]
        ]);
    }
}
