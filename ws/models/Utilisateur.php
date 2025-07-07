<?php
require_once __DIR__ . '/../db.php';

class Utilisateur
{
    public static function all(): array
    {
        return getDB()->query("SELECT * FROM banque_utilisateur")
                      ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $st = getDB()->prepare("SELECT * FROM banque_utilisateur WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $st = getDB()->prepare("SELECT * FROM banque_utilisateur WHERE email = ?");
        $st->execute([$email]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(string $nom, string $email, string $pwd): int
    {
        $hash = password_hash($pwd, PASSWORD_BCRYPT);
        $st   = getDB()->prepare("
            INSERT INTO banque_utilisateur (nom, email, mot_de_passe)
            VALUES (?, ?, ?)
        ");
        $st->execute([$nom, $email, $hash]);
        return (int) getDB()->lastInsertId();
    }

    public static function update(int $id, string $nom, string $email): void
    {
        $st = getDB()->prepare("
            UPDATE banque_utilisateur SET nom = ?, email = ? WHERE id = ?
        ");
        $st->execute([$nom, $email, $id]);
    }

    public static function delete(int $id): void
    {
        $st = getDB()->prepare("DELETE FROM banque_utilisateur WHERE id = ?");
        $st->execute([$id]);
    }
}
