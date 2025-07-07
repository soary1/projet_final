<?php
require_once 'models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Afficher la page de login pour les agents
     */
    public function showAgentLogin() {
        // Vérifier si déjà connecté
        if (isset($_SESSION['user']) && $_SESSION['user']['type'] === 'agent') {
            Flight::redirect('/ws/agent/dashboard');
            return;
        }
        
        include '../views/agents/login.php';
    }
    
    /**
     * Afficher la page de login pour les admins
     */
    public function showAdminLogin() {
        // Vérifier si déjà connecté
        if (isset($_SESSION['user']) && $_SESSION['user']['type'] === 'admin') {
            Flight::redirect('/projet_final/ws/admin/dashboard');
            return;
        }
        
        include '../views/admin/login.php';
    }
    
    /**
     * Traiter la connexion des agents
     */
    public function loginAgent() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            Flight::json([
                'success' => false,
                'message' => 'Email et mot de passe requis'
            ]);
            return;
        }
        
        $user = $this->userModel->authenticate($email, $password);
        
        if ($user && $user['type'] === 'agent') {
            $_SESSION['user'] = $user;
            Flight::json([
                'success' => true,
                'message' => 'Connexion réussie',
                'redirect' => '/projet_final/ws/agent/dashboard'
            ]);
        } else {
            Flight::json([
                'success' => false,
                'message' => 'Identifiants invalides ou accès non autorisé'
            ]);
        }
    }
    
    /**
     * Traiter la connexion des admins
     */
    public function loginAdmin() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            Flight::json([
                'success' => false,
                'message' => 'Email et mot de passe requis'
            ]);
            return;
        }
        
        $user = $this->userModel->authenticate($email, $password);
        
        if ($user && $user['type'] === 'admin') {
            $_SESSION['user'] = $user;
            Flight::json([
                'success' => true,
                'message' => 'Connexion réussie',
                'redirect' => '/projet_final/ws/admin/dashboard'
            ]);
        } else {
            Flight::json([
                'success' => false,
                'message' => 'Identifiants invalides ou accès non autorisé'
            ]);
        }
    }
    
    /**
     * Déconnexion
     */
    public function logout() {
        $userType = $_SESSION['user']['type'] ?? 'agent';
        session_destroy();
        
        if ($userType === 'admin') {
            Flight::redirect('/projet_final/ws/admin/login');
        } else {
            Flight::redirect('/projet_final/ws/agent/login');
        }
    }
    
    /**
     * Vérifier si l'utilisateur est un agent connecté
     */
    public static function requireAgent() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'agent') {
            Flight::json([
                'success' => false,
                'message' => 'Accès non autorisé',
                'redirect' => '/ws/agent/login'
            ], 401);
            return false;
        }
        return true;
    }
    
    /**
     * Vérifier si l'utilisateur est un admin connecté
     */
    public static function requireAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
            Flight::json([
                'success' => false,
                'message' => 'Accès non autorisé',
                'redirect' => '/ws/admin/login'
            ], 401);
            return false;
        }
        return true;
    }
}
