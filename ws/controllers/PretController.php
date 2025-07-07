<?php


require_once __DIR__ . '/../models/Pret.php';

class PretController
{

    
  public static function getAllPrets(): void
    {
        Flight::json(Pret::all());
    }
  
    public static function listPretByClient($id): void
    {
        Flight::json(Pret::byClient($id));
    }

    public static function createPret(int $idClient): void
    {
        $d  = Flight::request()->data;
        $id = Pret::create($idClient, $d['id_type_pret'], $d['montant']);
        Flight::json(['id' => $id], 200);
    }

    public static function deletePret(int $id): void
    {
        Pret::delete($id);
        Flight::json(null, 204);
    }
}
