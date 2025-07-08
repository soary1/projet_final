<?php
require_once __DIR__ . '/../models/Pret.php';

class SimulationController {

    public static function getClientsEtAgents() {
        $db = getDB();

        $clients = $db->query("
            SELECT c.id, u.nom 
            FROM banque_client c
            JOIN banque_utilisateur u ON u.id = c.id_utilisateur
        ")->fetchAll(PDO::FETCH_ASSOC);

        $agents = $db->query("
            SELECT a.id, u.nom 
            FROM banque_agent a
            JOIN banque_utilisateur u ON u.id = a.id_utilisateur
        ")->fetchAll(PDO::FETCH_ASSOC);

        Flight::json([
            'clients' => $clients,
            'agents' => $agents
        ]);
    }

    public static function simulerPret() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['prets'], $data['id_client'], $data['id_agent'])) {
                Flight::json(['success' => false, 'message' => 'Paramètres manquants.'], 400);
                return;
            }

            $db = getDB();
            $success = [];

            foreach ($data['prets'] as $pret) {
                $taux = floatval($pret['taux']);
                $duree = intval($pret['duree']);
                $assurance = floatval($pret['assurance']);
                $montant = floatval($pret['montant']);

                // Vérifie si le type de prêt existe
                $stmt = $db->prepare("SELECT id FROM banque_type_pret WHERE taux_interet = ? AND duree_mois = ? AND assurance = ?");
                $stmt->execute([$taux, $duree, $assurance]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row) {
                    Flight::json(['success' => false, 'message' => 'type_pret manquant']);
                    return;
                }

                $id_type_pret = $row['id'];

                // Insertion du prêt (assurance calculée, mais non stockée ici)
                $stmt = $db->prepare("
                    INSERT INTO banque_pret (id_client, id_agent, id_type_pret, montant, statut)
                    VALUES (?, ?, ?, ?, 'en attente')
                ");
                $stmt->execute([$data['id_client'], $data['id_agent'], $id_type_pret, $montant]);

                $success[] = true;
            }

            Flight::json(['success' => true, 'message' => 'Simulation enregistrée']);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }
}
