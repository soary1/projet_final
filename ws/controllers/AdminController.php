<?php
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../helpers/Utils.php';



class AdminController {
    public static function getAll() {
        $admins = Admin::getAll();
        Flight::json($admins);
    }

    public static function getById($id) {
        $admin = Admin::getById($id);
        Flight::json($admin);
    }

    public static function create() {
        $data = Flight::request()->data;
        $id = Admin::create($data);
        $dateFormatted = Utils::formatDate('2025-01-01');
        Flight::json(['message' => 'Étudiant ajouté', 'id' => $id]);
    }

    public static function update($id) {
        $data = Flight::request()->data;
        Admin::update($id, $data);
        Flight::json(['message' => 'Étudiant modifié']);
    }

    public static function delete($id) {
        Admin::delete($id);
        Flight::json(['message' => 'Étudiant supprimé']);
    }
    public static function login() {
        session_start();
        
        $data = Flight::request()->data;
        $email = $data->email ?? '';
        $password = $data->mot_de_passe ?? '';

        if (empty($email) || empty($password)) {
            Flight::json(['succes' => false, 'message' => 'Email et mot de passe requis'], 400);
            return;
        }

        $result = Admin::login($email, $password);

        if ($result['succes']) {
            $_SESSION['user'] = [
                'id' => $result['id'],
                'email' => $email,
                'role' => 'admin',
                'niveau_acces' => $result['niveau_acces'] ?? 'standard',
                'connecte' => true,
                'derniere_connexion' => date('Y-m-d H:i:s')
            ];
            Flight::json(['succes' => true, 'message' => 'Connexion réussie']);
        } else {
            Flight::json(['succes' => false, 'message' => 'Email ou mot de passe incorrect'], 401);
        }
    }
}
