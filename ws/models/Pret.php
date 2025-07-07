<?php
require_once __DIR__ . '/../db.php';

class Pret {
    public static function getPretsEnAttente() {
        $db = getDB();
        $stmt = $db->query("
            SELECT p.id, c.id AS id_client, u.nom AS nom_client, u.email,
                   tp.nom AS type_pret, p.montant, p.date_demande, p.statut
            FROM banque_pret p
            JOIN banque_client c ON p.id_client = c.id
            JOIN banque_utilisateur u ON c.id_utilisateur = u.id
            JOIN banque_type_pret tp ON p.id_type_pret = tp.id
            WHERE p.statut = 'en attente'
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function changerStatut($id, $nouveauStatut) {
        $ancien = 'en attente';
        $db = getDB();
        $stmt = $db->prepare("UPDATE banque_pret SET statut = ? WHERE id = ?");
        $stmt->execute([$nouveauStatut, $id]);

        $stmtHist = $db->prepare("
            INSERT INTO banque_historique_pret (id_pret, ancien_statut, nouveau_statut)
            VALUES (?, ?, ?)
        ");
        $stmtHist->execute([$id, $ancien, $nouveauStatut]);
    }

    public static function getTypesFond() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM banque_type_fond");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouterFond($data) {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO banque_fond (montant, id_type_fond, id_agent)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $data['montant'],
            $data['id_type_fond'],
            $data['id_agent']
        ]);
    }


    public static function trouverOuCreerTypePret($nom, $taux, $duree) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id FROM banque_type_pret WHERE taux_interet = ? AND duree_mois = ?");
        $stmt->execute([$taux, $duree]);
        $result = $stmt->fetch();

        if ($result) {
            return $result['id'];
        } else {
            $stmt = $db->prepare("INSERT INTO banque_type_pret (nom, taux_interet, duree_mois) VALUES (?, ?, ?)");
            $stmt->execute([$nom, $taux, $duree]);
            return $db->lastInsertId();
        }
    }

    public static function enregistrerPretSimule($idClient, $idAgent, $montant, $taux, $duree) {
        $db = getDB();
        $nomType = "Simulation $taux% sur $duree mois";
        $idTypePret = self::trouverOuCreerTypePret($nomType, $taux, $duree);

        $stmt = $db->prepare("INSERT INTO banque_pret (id_client, id_agent, id_type_pret, montant, statut) VALUES (?, ?, ?, ?, 'valide')");
        $stmt->execute([$idClient, $idAgent, $idTypePret, $montant]);
    }

    public static function existe($taux, $duree) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id FROM banque_type_pret WHERE taux_interet = ? AND duree_mois = ?");
        $stmt->execute([$taux, $duree]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function ajouterType($nom, $taux, $duree) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO banque_type_pret (nom, taux_interet, duree_mois) VALUES (?, ?, ?)");
        return $stmt->execute([$nom, $taux, $duree]);
    }


}
