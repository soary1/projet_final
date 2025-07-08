<?php

require_once __DIR__ . '/../models/TypePret.php';

class TypePretController 
{
    // GET /typepret
    public static function getAllTypesPret(): void
    {
        Flight::json(TypePret::all());
    }

    // GET /typepret/@id
    public static function getTypePretById($id): void 
    {
        $typePret = TypePret::getById($id);
        if ($typePret) {
            Flight::json($typePret);
        } else {
            Flight::halt(404, 'Type de prêt non trouvé');
        }
    }

    // POST /typepret
    public static function createTypePret(): void
    {
        $d = Flight::request()->data;

        $nom          = $d['nom'] ?? null;
        $taux         = $d['taux_interet'] ?? null;
        $duree        = $d['duree_mois'] ?? null;
        $delai_defaut = $d['delai_defaut'] ?? 0;
        $assurance    = $d['assurance'] ?? 0;

        if (!$nom || !$taux || !$duree) {
            Flight::json(['error' => 'Champs requis manquants'], 400);
            return;
        }

        $id = TypePret::create($nom, (float)$taux, (int)$duree, (int)$delai_defaut, $assurance);
        Flight::json(['message' => 'Type de prêt ajouté', 'id' => $id], 200);
    }
}
