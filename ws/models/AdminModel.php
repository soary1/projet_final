<?php
require_once __DIR__ . '/../db.php';

class Admin {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM banque_admin");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM banque_admin WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO banque_admin (id_utilisateur, niveau_acces) VALUES (?, ?)");
        $niveau_acces = isset($data->niveau_acces) ? $data->niveau_acces : 'standard';
        $stmt->execute([$data->id_utilisateur, $niveau_acces]);
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE banque_admin SET id_utilisateur = ?, niveau_acces = ? WHERE id = ?");
        $stmt->execute([$data->id_utilisateur, $data->niveau_acces, $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM banque_admin WHERE id = ?");
        $stmt->execute([$id]);
    }
    public static function login($email, $password) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM banque_admin WHERE email = ? AND password = ?");
        $stmt->execute([$email, $password]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return [
                'succes' => true,
                'id' => $data['id'],
                'role' => 'admin',
                'niveau_acces' => $data['niveau_acces'] ?? 'standard'
            ];
        } else {
            return ['succes' => false];
        }
    }
}
    