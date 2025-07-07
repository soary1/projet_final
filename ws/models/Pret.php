<?php
require_once __DIR__ . '/../db.php';


class Pret {
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
