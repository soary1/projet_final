<?php
require_once __DIR__ . '/../models/Pret.php';
require_once __DIR__ . '/../models/Fond.php';


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
    Flight::json(Pret::allWithTypePret()); 
}

  
    public static function listPretByClient($id): void
    {
        Flight::json(Pret::byClient($id));
    }
    public static function createPret(int $idClient): void
    {
        $d = Flight::request()->data;
        $montant = floatval($d['montant']);
        $date = $d['date_demande'];

        $disponible = Fond::getDisponibleAtDate($date);

        if ($montant > $disponible) {
            Flight::json(['error' => 'Fonds de l\'établissement insuffisants à cette date'], 400);
            return;
        }

        $id = Pret::create($idClient, $d['id_type_pret'], $montant, $date);
        Flight::json(['id' => $id], 200);
    }
    

    public static function deletePret(int $id): void
    {
        Pret::delete($id);
        Flight::json(null, 204);
    }


    public static function ajouterType() {
        $data  = Flight::request()->data;

        if (!isset($data['nom'], $data['taux_interet'], $data['duree_mois'], $data['assurance'])) {
            Flight::json(['success' => false, 'message' => 'Paramètres manquants.'], 400);
            return;
        }

        $nom = trim($data['nom']);
        $taux = floatval($data['taux_interet']);
        $duree = intval($data['duree_mois']);
        $assurance = floatval($data['assurance']);

        $dejaExistant = Pret::existe($taux, $duree, $assurance);
        if ($dejaExistant) {
            Flight::json(['success' => false, 'message' => 'Type de prêt similaire existe déjà.'], 409);
            return;
        }

        $ok = Pret::ajouterType($nom, $taux, $duree, $assurance);
        if ($ok) {
            Flight::json(['success' => true, 'message' => 'Type de prêt ajouté avec succès.']);
        } else {
            Flight::json(['success' => false, 'message' => 'Erreur lors de l\'ajout.'], 500);
        }
    }

}
