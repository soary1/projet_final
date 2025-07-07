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
}