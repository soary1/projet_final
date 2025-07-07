<?php


require_once __DIR__ . '/../models/TypePret.php';

class TypePretController 
{
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
    public static function getTypePretById($id): void {
    $typePret = TypePret::getById($id);
    if ($typePret) {
        Flight::json($typePret);
    } else {
        Flight::halt(404, 'Type de prêt non trouvé');
    }
}

}
