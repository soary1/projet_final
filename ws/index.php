<?php
require 'vendor/autoload.php';
require 'db.php';
<<<<<<< Updated upstream

Flight::route('GET /etudiants', function() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM etudiant");
    Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
});

Flight::route('GET /etudiants/@id', function($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM etudiant WHERE id = ?");
    $stmt->execute([$id]);
    Flight::json($stmt->fetch(PDO::FETCH_ASSOC));
});

Flight::route('POST /etudiants', function() {
    $data = Flight::request()->data;
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO etudiant (nom, prenom, email, age) VALUES (?, ?, ?, ?)");
    $stmt->execute([$data->nom, $data->prenom, $data->email, $data->age]);
    Flight::json(['message' => 'Étudiant ajouté', 'id' => $db->lastInsertId()]);
});

Flight::route('PUT /etudiants/@id', function($id) {
    $data = Flight::request()->data;
    $db = getDB();
    $stmt = $db->prepare("UPDATE etudiant SET nom = ?, prenom = ?, email = ?, age = ? WHERE id = ?");
    $stmt->execute([$data->nom, $data->prenom, $data->email, $data->age, $id]);
    Flight::json(['message' => 'Étudiant modifié']);
});

Flight::route('DELETE /etudiants/@id', function($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM etudiant WHERE id = ?");
    $stmt->execute([$id]);
    Flight::json(['message' => 'Étudiant supprimé']);
});

/* GET /typepret  →  liste de tous les types de prêt */
Flight::route('GET /typepret', function () {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM banque_type_pret ORDER BY id");
    Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
});

/* GET /prets        → liste de tous les prêts
 *     /prets?id_client=5   → filtre par client (optionnel)
 */
Flight::route('GET /prets', function () {
    $db        = getDB();
    $idClient  = Flight::request()->query['id_client'] ?? null;

    $sql  = 'SELECT p.*, tp.nom AS type_pret
             FROM banque_pret p
             JOIN banque_type_pret tp ON tp.id = p.id_type_pret';
    $args = [];
    if ($idClient) {
        $sql .= ' WHERE p.id_client = ?';
        $args[] = $idClient;
    }
    $sql .= ' ORDER BY p.date_demande DESC';

    $stmt = $db->prepare($sql);
    $stmt->execute($args);
    Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
});

/* GET /prets/{id}   → prêt unique */
Flight::route('GET /prets/@id', function ($id) {
    $db   = getDB();
    $stmt = $db->prepare(
        'SELECT p.*, tp.nom AS type_pret
         FROM banque_pret p
         JOIN banque_type_pret tp ON tp.id = p.id_type_pret
         WHERE p.id = ?'
    );
    $stmt->execute([$id]);
    Flight::json($stmt->fetch(PDO::FETCH_ASSOC));
});

/* POST /prets       → créer un prêt
   champs attendus : id_client, id_type_pret, montant
   id_agent est fixé ici à 1 pour l’exemple */
Flight::route('POST /prets', function () {
    $d  = Flight::request()->data;
    $db = getDB();

    $stmt = $db->prepare(
        'INSERT INTO banque_pret (id_client, id_agent, id_type_pret, montant)
         VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$d->id_client, 1, $d->id_type_pret, $d->montant]);

    Flight::json(['message' => 'Prêt ajouté', 'id' => $db->lastInsertId()]);
});
=======
require 'routes/etudiant_routes.php';
>>>>>>> Stashed changes

/* PUT /prets/{id}   → modifier un prêt */
Flight::route('PUT /prets/@id', function ($id) {
    $d  = Flight::request()->data;
    $db = getDB();

    $stmt = $db->prepare(
        'UPDATE banque_pret
         SET id_client = ?, id_type_pret = ?, montant = ?, statut = ?
         WHERE id = ?'
    );
    $stmt->execute([
        $d->id_client,
        $d->id_type_pret,
        $d->montant,
        $d->statut ?? 'en attente',
        $id
    ]);

    Flight::json(['message' => 'Prêt modifié']);
});

/* DELETE /prets/{id} → supprimer un prêt */
Flight::route('DELETE /prets/@id', function ($id) {
    $db = getDB();
    $stmt = $db->prepare('DELETE FROM banque_pret WHERE id = ?');
    $stmt->execute([$id]);
    Flight::json(['message' => 'Prêt supprimé']);
});

Flight::start();