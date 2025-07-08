<?php
require_once 'models/Remboursement.php';
require_once 'controllers/AuthController.php';

class RemboursementController {

    /**
     * Lister tous les remboursements
     */
    public function getAll() {
        if (!AuthController::requireAdmin()) return;

        try {
            $remboursements = Remboursement::all();
            Flight::json([
                'success' => true,
                'data' => $remboursements
            ]);
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des remboursements'
            ]);
        }
    }

    /**
     * Récupérer les remboursements d'un prêt donné
     */
    public function getByPret($idPret) {
        if (!AuthController::requireAdmin()) return;

        try {
            $data = Remboursement::byPret($idPret);
            Flight::json([
                'success' => true,
                'data' => $data
            ]);
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des remboursements du prêt'
            ]);
        }
    }

    /**
     * Créer un remboursement
     */
    public function create() {
        if (!AuthController::requireAdmin()) return;

        $idPret = $_POST['id_pret'] ?? null;
        $datePaiement = $_POST['date_paiement'] ?? null;

        if (!$idPret || !$datePaiement) {
            Flight::json([
                'success' => false,
                'message' => 'ID prêt et date de paiement sont requis'
            ]);
            return;
        }


        try {
            $ok = Remboursement::create($idPret, $datePaiement);
            if ($ok) {
                Flight::json([
                    'success' => true,
                    'message' => 'Remboursement enregistré avec succès'
                ]);
            } else {
                throw new Exception("Erreur d'insertion");
            }
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la création du remboursement'
            ]);
        }
    }

    /**
     * Supprimer un remboursement
     */
    public function delete($id) {
        if (!AuthController::requireAdmin()) return;

        try {
            Remboursement::delete($id);
            Flight::json([
                'success' => true,
                'message' => 'Remboursement supprimé'
            ]);
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ]);
        }
    }

    /**
     * Liste des remboursements en retard
     */
    public function getRetards() {
        if (!AuthController::requireAdmin()) return;

        try {
            $data = Remboursement::getRetards();
            Flight::json([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des retards'
            ]);
        }
    }
    public function rembourserTout($idPret) {
    if (!AuthController::requireAdmin()) return;

    $ok = Remboursement::rembourserTout($idPret);
    if ($ok) {
        Flight::json(['success' => true, 'message' => 'Toutes les mensualités ont été générées.']);
    } else {
        Flight::json(['success' => false, 'message' => 'Erreur lors du remboursement total.']);
    }
}
public function rembourserNmois($idPret) {
    if (!AuthController::requireAdmin()) return;

    $nb = $_POST['nb'] ?? null;
    if (!$nb || $nb <= 0) {
        Flight::json(['success' => false, 'message' => 'Nombre de mois invalide.']);
        return;
    }

    $ok = Remboursement::rembourserNmois($idPret, (int)$nb);
    if ($ok) {
        Flight::json(['success' => true, 'message' => "$nb remboursement(s) ajouté(s)."]);
    } else {
        Flight::json(['success' => false, 'message' => 'Erreur pendant l\'ajout.']);
    }
}

    public static function interetsReels() {
        // Lecture des paramètres GET
        $moisDebut = Flight::request()->query['mois_debut'];
        $anneeDebut = Flight::request()->query['annee_debut'];
        $moisFin = Flight::request()->query['mois_fin'];
        $anneeFin = Flight::request()->query['annee_fin'];

        if (!$moisDebut || !$anneeDebut || !$moisFin || !$anneeFin) {
            Flight::json(['success' => false, 'message' => 'Période invalide.'], 400);
            return;
        }

        // Construction des dates au format Y-m-d
        $dateDebut = sprintf('%04d-%02d-01', $anneeDebut, $moisDebut);
        $dateFin = date("Y-m-t", strtotime(sprintf('%04d-%02d-01', $anneeFin, $moisFin))); // dernier jour du mois

        // Récupération depuis le modèle
        $resultats = Remboursement::getInteretsParMois($dateDebut, $dateFin);

        Flight::json(['success' => true, 'data' => $resultats]);
    }


}