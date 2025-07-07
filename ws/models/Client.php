<?php
require_once __DIR__ . '/../db.php';


class Client {
    public static function all(): array {
        return getDB()->query("SELECT * FROM banque_client")
                      ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array {
    $st = getDB()->prepare("
        SELECT c.*, u.nom as nom_utilisateur, u.email
        FROM banque_client c
        JOIN banque_utilisateur u ON c.id_utilisateur = u.id
        WHERE c.id = ?
    ");
    $st->execute([$id]);
    return $st->fetch(PDO::FETCH_ASSOC) ?: null;
}


    public static function create(string $nom, string $email): int {
        $st = getDB()->prepare("INSERT INTO banque_client (nom,email) VALUES (?,?)");
        $st->execute([$nom, $email]);
        return (int) getDB()->lastInsertId();
    }

    public static function delete(int $id): void {
        $st = getDB()->prepare("DELETE FROM banque_client WHERE id = ?");
        $st->execute([$id]);        
    }
}
