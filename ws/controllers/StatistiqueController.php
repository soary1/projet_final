<?php
require_once __DIR__ . '/../db.php';
require_once("models/Fond.php");
require_once("models/Pret.php");

class StatistiqueController {
    public static function getDisponibilites() {
        $pdo = getDB();

        $debut = $_GET['debut'] ?? null;  // Ex: "2024-01"
        $fin   = $_GET['fin'] ?? null;    // Ex: "2024-06"

        if (!$debut || !$fin) {
            Flight::json(['error' => 'Paramètres manquants'], 400);
            return;
        }

        $dateDebut = $debut . '-01';
        $dateFin = date('Y-m-d', strtotime($fin . '-01 +1 month'));

        try {
            // Appelle la procédure stockée avec les dates
            $stmt = $pdo->prepare("CALL synthese_mensuelle(:debut, :fin)");
            $stmt->execute([
                ':debut' => $dateDebut,
                ':fin'   => $dateFin
            ]);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Flight::json($result);
        } catch (Throwable $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}
