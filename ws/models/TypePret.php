<?php
require_once __DIR__ . '/../db.php';

class TypePret
{
    public static function all(): array
    {
        return getDB()->query("SELECT * FROM banque_type_pret")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById(int $id): ?array
    {
        $st = getDB()->prepare("SELECT * FROM banque_type_pret WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(string $nom, float $taux, int $duree, int $delai_defaut, $assurance): int
    {
        $db = getDB(); 
        $st = $db->prepare("
            INSERT INTO banque_type_pret (nom, taux_interet, duree_mois, delai_defaut, assurance)
            VALUES (?, ?, ?, ?, ?)
        ");
        $st->execute([$nom, $taux, $duree, $delai_defaut, $assurance]);
        return (int) $db->lastInsertId(); 
    }

}
