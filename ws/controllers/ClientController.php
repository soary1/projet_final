<?php


require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../helpers/Utils.php';



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
        $d  = Flight::request()->data;
        $id = TypePret::create($d['libelle'], $d['taux']);
        Flight::json(['id' => $id], 201);
    }
}