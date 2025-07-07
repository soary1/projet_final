<?php


require_once __DIR__ . '/../models/Client.php';



class ClientController
{
    public static function getAllClients(): void
    {
        Flight::json(Client::all());
    }

    public static function createClient(): void
    {
        $d  = Flight::request()->data;
        $id = Client::create($d['nom'], $d['email']);
        Flight::json(['id' => $id], 201);
    }

    public static function deleteClient(int $id): void
    {
        Client::delete($id);
        Flight::json(null, 204);
    }

    /* -------- TYPES DE PRÊT -------- */

    public static function getAllTypesPret(): void
    {
        Flight::json(TypePret::all());
    }

       public static function createTypePret(): void
{
    $d = Flight::request()->data;

    // Vérification des champs nécessaires
    if (!isset($d['nom'], $d['taux_interet'], $d['duree_mois'], $d['delai_defaut'])) {
        Flight::json(['success' => false, 'message' => 'Champs manquants'], 400);
        return;
    }

    // Création du type de prêt
    $id = TypePret::create(
        $d['nom'],
        (float) $d['taux_interet'],
        (int) $d['duree_mois'],
        (int) $d['delai_defaut']
    );

    Flight::json(['success' => true, 'id' => $id], 201);
}


    public static function getClientById($id): void {
        $client = Client::find((int)$id);
        if ($client) {
            Flight::json($client);
        } else {
            Flight::halt(404, "Client introuvable");
        }
    }
}