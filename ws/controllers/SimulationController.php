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

    public static function getTypesPret() {
    try {
        $db = getDB();
        $types = $db->query("
            SELECT id, nom, taux_interet, duree_mois, assurance, delai_defaut
            FROM banque_type_pret
        ")->fetchAll(PDO::FETCH_ASSOC);

        Flight::json($types);
    } catch (Exception $e) {
        Flight::json(['success' => false, 'message' => $e->getMessage()], 500);
    }
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
            $assurance = floatval($pret['assurance'] ?? 0);
            $montant = floatval($pret['montant']);

            // 1. Vérifier ou créer le type de prêt
            $stmt = $db->prepare("
                SELECT id FROM banque_type_pret
                WHERE taux_interet = ? AND duree_mois = ? AND assurance = ?
            ");
            $stmt->execute([$taux, $duree, $assurance]);
            $typePret = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$typePret) {
                // Créer automatiquement le type de prêt avec nom auto-généré
                $nom = "Auto {$taux}% / {$duree} mois / {$assurance}%";
                $stmt = $db->prepare("
                    INSERT INTO banque_type_pret (nom, taux_interet, duree_mois, assurance)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$nom, $taux, $duree, $assurance]);
                $id_type_pret = $db->lastInsertId();
            } else {
                $id_type_pret = $typePret['id'];
            }

            // 2. Calculs
            $taux_mensuel = $taux / 100 / 12;
            $interet_total = 0;
            $mensualite = 0;

            if ($taux_mensuel > 0 && $duree > 0) {
                $mensualite = $montant * $taux_mensuel / (1 - pow(1 + $taux_mensuel, -$duree));
                $interet_total = ($mensualite * $duree) - $montant;
            }

            $interet_mensuel = $interet_total / $duree;

            // 3. Insertion dans banque_simulation
            $stmt = $db->prepare("
                INSERT INTO banque_simulation 
                (id_client, id_agent, montant, taux_annuel, duree_mois, assurance, mensualite, interet_total, interet_mensuel)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['id_client'],
                $data['id_agent'],
                $montant,
                $taux,
                $duree,
                $assurance,
                $mensualite,
                $interet_total,
                $interet_mensuel
            ]);

            $success[] = true;
        }

        Flight::json(['success' => true, 'message' => 'Simulation(s) enregistrée(s) et type(s) de prêt créés si nécessaire']);
    } catch (Exception $e) {
        Flight::json(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
    }
}
public static function afficherComparaison() {
    // Vérification si le paramètre 'ids' est passé dans l'URL
    if (!isset($_GET['ids']) || empty($_GET['ids'])) {
        Flight::json(['success' => false, 'message' => 'Paramètre "ids" manquant.'], 400);
        return;
    }

    // Récupérer et valider les IDs dans l'URL
    $ids = array_filter(explode(',', $_GET['ids']), function($id) {
        return ctype_digit($id); // sécurise contre les injections
    });

    // Si moins de 2 simulations sont sélectionnées
    if (count($ids) < 2) {
        Flight::json(['success' => false, 'message' => 'Au moins deux simulations sont nécessaires.'], 400);
        return;
    }

    // Créer des placeholders pour la requête SQL
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    try {
        $db = getDB();
        // Préparer la requête SQL pour récupérer les simulations à partir des IDs
        $stmt = $db->prepare("SELECT * FROM banque_simulation WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $simulations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Si les simulations ne sont pas trouvées
        if (count($simulations) !== 2) {
            Flight::json(['success' => false, 'message' => 'Il n\'y a pas exactement deux simulations correspondantes.'], 404);
            return;
        }

        // Retourner les simulations en format JSON
        Flight::json(['success' => true, 'simulations' => $simulations]);

    } catch (PDOException $e) {
        Flight::json(['success' => false, 'message' => 'Erreur base de données : ' . $e->getMessage()], 500);
    }
}



// Ex: SimulationController.php
public static function lister() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM banque_simulation ORDER BY id DESC");
    $simulations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    Flight::json($simulations);
}






}
