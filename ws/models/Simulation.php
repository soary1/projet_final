<?php
require_once __DIR__ . '/../db.php';
 class Simulation {

public static function enregistrerSimulation() {
    try {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id_client'], $data['id_agent'], $data['montant'], $data['taux_annuel'], $data['duree_mois'], $data['type_interet'])) {
            Flight::json(['success' => false, 'message' => 'Paramètres incomplets'], 400);
            return;
        }

        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO banque_simulation (
                id_client, id_agent, montant, taux_annuel, duree_mois, assurance, 
                type_interet, mensualite, interet_total, interet_mensuel
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['id_client'],
            $data['id_agent'],
            $data['montant'],
            $data['taux_annuel'],
            $data['duree_mois'],
            $data['assurance'] ?? 0,
            $data['mensualite'] ?? null,
            $data['interet_total'] ?? null,
            $data['interet_mensuel'] ?? null
        ]);

        Flight::json(['success' => true, 'message' => 'Simulation enregistrée.']);
    } catch (Exception $e) {
        Flight::json(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
    }
}
 
}