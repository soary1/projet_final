<?php
require_once __DIR__ . '/../models/Pret.php';

class PretController {
    public static function getEnAttente() {
        $prets = Pret::getPretsEnAttente();
        Flight::json($prets);
    }

    public static function valider($id) {
        Pret::changerStatut($id, 'valide');
        Flight::json(['message' => 'Prêt validé']);
    }

    public static function refuser($id) {
        Pret::changerStatut($id, 'refuse');
        Flight::json(['message' => 'Prêt refusé']);
    }

    public static function getTypesFond() {
        $types = Pret::getTypesFond();
        Flight::json($types);
    }

    public static function ajouterFond() {
        parse_str(file_get_contents("php://input"), $data);
        Pret::ajouterFond($data);
        Flight::json(['message' => 'Fond ajouté']);
    }
    
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
