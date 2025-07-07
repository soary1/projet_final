<?php
require 'vendor/autoload.php';
require 'db.php';
<<<<<<< Updated upstream

// Route de connexion
Flight::route('POST /connexion', function() {
    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input);
        
        if (!$data || !isset($data->email) || !isset($data->mot_de_passe)) {
            Flight::json(['succes' => false, 'message' => 'Email et mot de passe requis'], 400);
            return;
        }
        
        $db = getDB();
        
        // Rechercher l'utilisateur
        $stmt = $db->prepare("SELECT * FROM banque_utilisateur WHERE email = ?");
        $stmt->execute([$data->email]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$utilisateur) {
            Flight::json(['succes' => false, 'message' => 'Utilisateur non trouvé'], 401);
            return;
        }
        
        // Vérifier le mot de passe (compatibilité hash et plain text)
        $motDePasseValide = false;
        
        // D'abord essayer password_verify (pour les mots de passe hashés)
        if (password_verify($data->mot_de_passe, $utilisateur['mot_de_passe'])) {
            $motDePasseValide = true;
        }
        // Si ça ne marche pas, essayer la comparaison directe (pour les anciens mots de passe)
        elseif ($data->mot_de_passe === $utilisateur['mot_de_passe']) {
            $motDePasseValide = true;
            
            // Optionnel: Hasher le mot de passe pour la prochaine fois
            $nouveauHash = password_hash($data->mot_de_passe, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE banque_utilisateur SET mot_de_passe = ? WHERE id = ?");
            $stmt->execute([$nouveauHash, $utilisateur['id']]);
        }
        
        if (!$motDePasseValide) {
            Flight::json(['succes' => false, 'message' => 'Mot de passe incorrect'], 401);
            return;
        }
        
        // Détecter automatiquement le rôle de l'utilisateur
        $role = null;
        $roleInfo = [];
        
        // Vérifier si c'est un admin
        $stmt = $db->prepare("SELECT * FROM banque_admin WHERE id_utilisateur = ?");
        $stmt->execute([$utilisateur['id']]);
        $adminData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($adminData) {
            $role = 'admin';
            $roleInfo = $adminData;
        }
        
        // Vérifier si c'est un agent
        if (!$role) {
            $stmt = $db->prepare("SELECT * FROM banque_agent WHERE id_utilisateur = ?");
            $stmt->execute([$utilisateur['id']]);
            $agentData = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($agentData) {
                $role = 'agent';
                $roleInfo = $agentData;
            }
        }
        
        // Vérifier si c'est un client
        if (!$role) {
            $stmt = $db->prepare("SELECT * FROM banque_client WHERE id_utilisateur = ?");
            $stmt->execute([$utilisateur['id']]);
            $clientData = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($clientData) {
                $role = 'client';
                $roleInfo = $clientData;
            }
        }
        
        if (!$role) {
            Flight::json(['succes' => false, 'message' => 'Aucun rôle assigné à cet utilisateur'], 403);
            return;
        }
        
        // Connexion réussie
        $response = [
            'succes' => true, 
            'message' => 'Connexion réussie',
            'id' => $utilisateur['id'],
            'nom' => $utilisateur['nom'],
            'email' => $utilisateur['email'],
            'role' => $role
        ];
        
        // Ajouter des informations spécifiques au rôle
        if ($role === 'admin') {
            $response['niveau_acces'] = $roleInfo['niveau_acces'] ?? 'standard';
        } elseif ($role === 'agent') {
            $response['matricule'] = $roleInfo['matricule'] ?? '';
        } elseif ($role === 'client') {
            $response['profession'] = $roleInfo['profession'] ?? '';
        }
        
        Flight::json($response);
        
    } catch (Exception $e) {
        Flight::json(['succes' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()], 500);
    }
});

// Routes pour les types de prêt
Flight::route('GET /typepret', function() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM banque_type_pret");
    Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
});

Flight::route('GET /typepret/@id', function($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM banque_type_pret WHERE id = ?");
    $stmt->execute([$id]);
    Flight::json($stmt->fetch(PDO::FETCH_ASSOC));
});

Flight::route('POST /typepret', function() {
    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input);
        
        if (!$data) {
            Flight::json(['error' => 'Données JSON invalides'], 400);
            return;
        }
        
        if (!isset($data->nom) || !isset($data->taux_interet) || !isset($data->duree_mois)) {
            Flight::json(['error' => 'Champs requis manquants: nom, taux_interet, duree_mois'], 400);
            return;
        }
        
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO banque_type_pret (nom, taux_interet, duree_mois) VALUES (?, ?, ?)");
        $stmt->execute([$data->nom, $data->taux_interet, $data->duree_mois]);
        Flight::json(['message' => 'Type de prêt ajouté', 'id' => $db->lastInsertId()]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('PUT /typepret/@id', function($id) {
    $data = Flight::request()->data;
    $db = getDB();
    $stmt = $db->prepare("UPDATE banque_type_pret SET nom = ?, taux_interet = ?, duree_mois = ? WHERE id = ?");
    $stmt->execute([$data->nom, $data->taux_interet, $data->duree_mois, $id]);
    Flight::json(['message' => 'Type de prêt modifié']);
});

Flight::route('DELETE /typepret/@id', function($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM banque_type_pret WHERE id = ?");
    $stmt->execute([$id]);
    Flight::json(['message' => 'Type de prêt supprimé']);
});

// Routes pour les utilisateurs
Flight::route('GET /utilisateurs', function() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM banque_utilisateur");
    Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
});

Flight::route('POST /utilisateurs', function() {
    $data = Flight::request()->data;
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO banque_utilisateur (nom, email, mot_de_passe) VALUES (?, ?, ?)");
    $stmt->execute([$data->nom, $data->email, password_hash($data->mot_de_passe, PASSWORD_DEFAULT)]);
    Flight::json(['message' => 'Utilisateur ajouté', 'id' => $db->lastInsertId()]);
});

// Routes pour les clients
Flight::route('GET /clients', function() {
    $db = getDB();
    $stmt = $db->query("SELECT c.*, u.nom, u.email FROM banque_client c JOIN banque_utilisateur u ON c.id_utilisateur = u.id");
    Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
});

Flight::route('POST /clients', function() {
    $data = Flight::request()->data;
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO banque_client (id_utilisateur, profession, revenu_mensuel) VALUES (?, ?, ?)");
    $stmt->execute([$data->id_utilisateur, $data->profession, $data->revenu_mensuel]);
    Flight::json(['message' => 'Client ajouté', 'id' => $db->lastInsertId()]);
});

// Routes pour les prêts
Flight::route('GET /prets', function() {
    $db = getDB();
    $stmt = $db->query("SELECT p.*, c.id as client_id, u.nom as client_nom, tp.nom as type_pret_nom 
                       FROM banque_pret p 
                       JOIN banque_client c ON p.id_client = c.id 
                       JOIN banque_utilisateur u ON c.id_utilisateur = u.id 
                       JOIN banque_type_pret tp ON p.id_type_pret = tp.id");
    Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
});

Flight::route('POST /prets', function() {
    $data = Flight::request()->data;
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO banque_pret (id_client, id_agent, id_type_pret, montant, statut) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$data->id_client, $data->id_agent, $data->id_type_pret, $data->montant, $data->statut ?? 'en attente']);
    Flight::json(['message' => 'Prêt ajouté', 'id' => $db->lastInsertId()]);
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