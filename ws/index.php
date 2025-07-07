<?php
require 'vendor/autoload.php';
require 'db.php';

Flight::route('GET /prets/en-attente', function () {
    $db = getDB();
    $stmt = $db->query("
        SELECT p.id, c.id AS id_client, u.nom AS nom_client, u.email,
               tp.nom AS type_pret, p.montant, p.date_demande, p.statut
        FROM banque_pret p
        JOIN banque_client c ON p.id_client = c.id
        JOIN banque_utilisateur u ON c.id_utilisateur = u.id
        JOIN banque_type_pret tp ON p.id_type_pret = tp.id
        WHERE p.statut = 'en attente'
    ");
    Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
});

Flight::route('POST /pret/valider/@id', function ($id) {
    $db = getDB();

    // Mettre à jour le statut
    $stmt = $db->prepare("UPDATE banque_pret SET statut = 'valide' WHERE id = ?");
    $stmt->execute([$id]);

    // Historique
    $stmtHist = $db->prepare("
        INSERT INTO banque_historique_pret (id_pret, ancien_statut, nouveau_statut)
        VALUES (?, 'en attente', 'valide')
    ");
    $stmtHist->execute([$id]);

    Flight::json(['message' => 'Prêt validé']);
});

Flight::route('POST /pret/refuser/@id', function ($id) {
    $db = getDB();

    $stmt = $db->prepare("UPDATE banque_pret SET statut = 'refuse' WHERE id = ?");
    $stmt->execute([$id]);

    $stmtHist = $db->prepare("
        INSERT INTO banque_historique_pret (id_pret, ancien_statut, nouveau_statut)
        VALUES (?, 'en attente', 'refuse')
    ");
    $stmtHist->execute([$id]);

    Flight::json(['message' => 'Prêt refusé']);
});

Flight::route('GET /typefond', function () {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM banque_type_fond");
    Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
});


Flight::route('POST /fond', function () {
    parse_str(file_get_contents("php://input"), $data);

    $montant = $data['montant'] ?? null;
    $id_type_fond = $data['id_type_fond'] ?? null;
    $id_agent = $data['id_agent'] ?? null;

    $db = getDB();
    $stmt = $db->prepare("
        INSERT INTO banque_fond (montant, id_type_fond, id_agent)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$montant, $id_type_fond, $id_agent]);

    Flight::json(['message' => 'Fond ajouté']);
});

// Flight::set('flight.base_url', '/projet_final'); 
Flight::start();
