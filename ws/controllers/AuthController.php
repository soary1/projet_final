    <?php
    require_once __DIR__ . '/../models/Utilisateur.php';

    class AuthController
    {
        private $userModel;
    
        public function __construct() {
            $this->userModel = new User();
        }
        /* POST /connexion */
        public static function login(): void
        {
            $data = Flight::request()->data;

            // Vérification des champs requis
            if (!isset($data['email']) || !isset($data['mot_de_passe'])) {
                Flight::json(['succes' => false, 'message' => 'Email et mot de passe requis'], 400);
                return;
            }

            // Récupération de l'utilisateur
            $u = Utilisateur::findByEmail($data['email']);
            if (!$u) {
                Flight::json(['succes' => false, 'message' => 'Utilisateur non trouvé'], 401);
                return;
            }

            $motDePasseSaisi   = $data['mot_de_passe'];
            $motDePasseEnBase  = $u['mot_de_passe'];

            // 1. Vérifie d'abord avec password_verify (cas hashé)
            $pwdOk = password_verify($motDePasseSaisi, $motDePasseEnBase);

            // 2. Sinon, compare directement (ancien mot de passe en clair)
            if (!$pwdOk && $motDePasseSaisi === $motDePasseEnBase) {
                $pwdOk = true;

                // ⚠️ Migration automatique vers un mot de passe hashé
                $hash = password_hash($motDePasseSaisi, PASSWORD_BCRYPT);
                $stmt = getDB()->prepare("UPDATE banque_utilisateur SET mot_de_passe = ? WHERE id = ?");
                $stmt->execute([$hash, $u['id']]);
            }

            if (!$pwdOk) {
                Flight::json(['succes' => false, 'message' => 'Mot de passe incorrect'], 401);
                return;
            }

            // Détection du rôle
            $db = getDB();
            $role = null;
            $info = [];

            foreach ([
                'admin'  => 'banque_admin',
                'agent'  => 'banque_agent',
                'client' => 'banque_client'
            ] as $r => $table) {
                $st = $db->prepare("SELECT * FROM $table WHERE id_utilisateur = ?");
                $st->execute([$u['id']]);
                if ($row = $st->fetch(PDO::FETCH_ASSOC)) {
                    $role = $r;
                    $info = $row;
                    break;
                }
            }

            if (!$role) {
                Flight::json(['succes' => false, 'message' => 'Aucun rôle assigné à cet utilisateur'], 403);
                return;
            }

            // Réponse finale
            $response = [
                'succes' => true,
                'message' => 'Connexion réussie',
                'id' => $u['id'],
                'nom' => $u['nom'],
                'email' => $u['email'],
                'role' => $role
            ];

            // Ajout d'informations selon le rôle
            $response += match($role) {
                'admin'  => ['niveau_acces' => $info['niveau_acces'] ?? 'standard'],
                'agent'  => ['matricule' => $info['matricule'] ?? ''],
                'client' => ['profession' => $info['profession'] ?? ''],
                default  => []
            };

            Flight::json($response);
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
