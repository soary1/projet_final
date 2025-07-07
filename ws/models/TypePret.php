<?php
require_once __DIR__ . '/../db.php';

class TypePret {
    public static function all(): array {
        return getDB()->query("SELECT * FROM banque_type_pret")
                      ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(string $libelle, float $taux): int {
        $st = getDB()->prepare(
            "INSERT INTO banque_type_pret (libelle,taux) VALUES (?,?)"
        );
        $st->execute([$libelle, $taux]);
        return (int) getDB()->lastInsertId();
    }
}
