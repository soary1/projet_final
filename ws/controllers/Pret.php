<?php


require_once __DIR__ . '/../models/Pret.php';
require_once __DIR__ . '/../helpers/Utils.php';

class Pret 
{
  public static function listPretByClient(int $idClient): void
    {
        Flight::json(Pret::byClient($idClient));
    }

    public static function createPret(int $idClient): void
    {
        $d  = Flight::request()->data;
        $id = Pret::create($idClient, $d['id_type_pret'], $d['montant']);
        Flight::json(['id' => $id], 201);
    }

    public static function deletePret(int $id): void
    {
        Pret::delete($id);
        Flight::json(null, 204);
    }
}
