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

  public static function all(): array {
        return getDB()->query("SELECT * FROM banque_pret")
                      ->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function byClient(int $idClient): array {
        $st = getDB()->prepare("
            SELECT p.*, t.nom, t.taux_interet
            FROM banque_pret p
            JOIN banque_type_pret t ON t.id = p.id_type_pret
            WHERE p.id_client = ?
        ");
        $st->execute([$idClient]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(int $idClient, int $idTypePret, float $montant): int {
        $st = getDB()->prepare("
            INSERT INTO banque_pret (montant, date_demande, id_client, id_type_pret)
            VALUES (?, CURDATE(), ?, ?)
        ");
        $st->execute([$montant, $idClient, $idTypePret]);
        return (int) getDB()->lastInsertId();
    }

    public static function delete(int $id): void {
        $st = getDB()->prepare("DELETE FROM banque_pret WHERE id = ?");
        $st->execute([$id]);
    }
}
