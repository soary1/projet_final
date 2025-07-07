<?php
require_once 'controllers/AuthController.php';
require_once 'models/User.php';

class AdminController {
    private $userModel;
    
    public function __construct() {
        // Le constructeur ne vérifie plus l'authentification
        // Chaque méthode qui en a besoin la vérifiera individuellement
        $this->userModel = new User();
    }
    
    /**
     * Afficher le dashboard des admins
     */
    public function dashboard() {
        if (!AuthController::requireAdmin()) {
            return;
        }
        $user = $_SESSION['user'];
        include '../views/admin/dashboard.php';
    }
    
    /**
     * Récupérer les statistiques globales
     */
    public function getGlobalStats() {
        if (!AuthController::requireAdmin()) {
            return;
        }
        
        try {
            $db = getDB();
            
            // Statistiques générales
            $stats = [];
            
            // Nombre total d'utilisateurs
            $stmt = $db->query("SELECT COUNT(*) as total FROM banque_utilisateur");
            $stats['total_utilisateurs'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Nombre d'agents
            $stmt = $db->query("SELECT COUNT(*) as total FROM banque_agent");
            $stats['total_agents'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Nombre de clients
            $stmt = $db->query("SELECT COUNT(*) as total FROM banque_client");
            $stats['total_clients'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Statistiques des prêts
            $stmt = $db->query("
                SELECT COUNT(*) as total_prets,
                       SUM(CASE WHEN statut = 'approuvé' THEN 1 ELSE 0 END) as prets_approuves,
                       SUM(CASE WHEN statut = 'en attente' THEN 1 ELSE 0 END) as prets_en_attente,
                       SUM(CASE WHEN statut = 'rejeté' THEN 1 ELSE 0 END) as prets_rejetes,
                       SUM(CASE WHEN statut = 'approuvé' THEN montant ELSE 0 END) as montant_total_approuve
                FROM banque_pret
            ");
            $pretStats = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats = array_merge($stats, $pretStats);
            
            // Fonds disponibles
            $stmt = $db->query("
                SELECT SUM(montant) as total_fonds 
                FROM banque_fond
            ");
            $fonds = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['total_fonds'] = $fonds['total_fonds'] ?? 0;
            
            Flight::json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ]);
        }
    }
    
    /**
     * Récupérer tous les agents
     */
    public function getAgents() {
        if (!AuthController::requireAdmin()) {
            return;
        }
        
        try {
            $db = getDB();
            
            $stmt = $db->query("
                SELECT a.id, a.matricule, a.date_embauche,
                       u.nom, u.email,
                       COUNT(p.id) as nombre_prets
                FROM banque_agent a
                JOIN banque_utilisateur u ON a.id_utilisateur = u.id
                LEFT JOIN banque_pret p ON a.id = p.id_agent
                GROUP BY a.id, a.matricule, a.date_embauche, u.nom, u.email
                ORDER BY u.nom
            ");
            $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Flight::json([
                'success' => true,
                'data' => $agents
            ]);
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des agents'
            ]);
        }
    }
      /**
     * Créer un nouvel agent
     */
    public function createAgent() {
        if (!AuthController::requireAdmin()) {
            return;
        }
        
        $nom = $_POST['nom'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $matricule = $_POST['matricule'] ?? '';

        if (empty($nom) || empty($email) || empty($password) || empty($matricule)) {
            Flight::json([
                'success' => false,
                'message' => 'Tous les champs sont requis'
            ]);
            return;
        }
        
        $userId = $this->userModel->createAgent($nom, $email, $password, $matricule);
        
        if ($userId) {
            Flight::json([
                'success' => true,
                'message' => 'Agent créé avec succès'
            ]);
        } else {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'agent'
            ]);
        }
    }
    
    /**
     * Récupérer tous les prêts (vue globale)
     */
    public function getAllPrets() {
        try {
            $db = getDB();
            
            $stmt = $db->query("
                SELECT p.id, p.montant, p.date_demande, p.statut,
                       uc.nom as client_nom, uc.email as client_email,
                       ua.nom as agent_nom,
                       tp.nom as type_pret, tp.taux_interet, tp.duree_mois
                FROM banque_pret p
                JOIN banque_client c ON p.id_client = c.id
                JOIN banque_utilisateur uc ON c.id_utilisateur = uc.id
                JOIN banque_agent a ON p.id_agent = a.id
                JOIN banque_utilisateur ua ON a.id_utilisateur = ua.id
                JOIN banque_type_pret tp ON p.id_type_pret = tp.id
                ORDER BY p.date_demande DESC
            ");
            $prets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Flight::json([
                'success' => true,
                'data' => $prets
            ]);
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des prêts'
            ]);
        }
    }
    
    /**
     * Récupérer les types de prêts
     */
    public function getTypesPrets() {
        try {
            $db = getDB();
            
            $stmt = $db->query("
                SELECT * FROM banque_type_pret 
                ORDER BY nom
            ");
            $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Flight::json([
                'success' => true,
                'data' => $types
            ]);
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des types de prêts'
            ]);
        }
    }
    
    /**
     * Créer un nouveau type de prêt
     */
    public function createTypePret() {
        $nom = $_POST['nom'] ?? '';
        $tauxInteret = $_POST['taux_interet'] ?? '';
        $dureeMois = $_POST['duree_mois'] ?? '';
        
        if (empty($nom) || empty($tauxInteret) || empty($dureeMois)) {
            Flight::json([
                'success' => false,
                'message' => 'Tous les champs sont requis'
            ]);
            return;
        }
        
        try {
            $db = getDB();
            
            $stmt = $db->prepare("
                INSERT INTO banque_type_pret (nom, taux_interet, duree_mois)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$nom, $tauxInteret, $dureeMois]);
            
            Flight::json([
                'success' => true,
                'message' => 'Type de prêt créé avec succès'
            ]);
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la création du type de prêt'
            ]);
        }
    }
    
    /**
     * Ajouter des fonds
     */
    public function addFonds() {
        $montant = $_POST['montant'] ?? '';
        $typeFondId = $_POST['type_fond_id'] ?? '';
        
        if (empty($montant) || empty($typeFondId)) {
            Flight::json([
                'success' => false,
                'message' => 'Montant et type de fond requis'
            ]);
            return;
        }
        
        try {
            $db = getDB();
            $adminId = $_SESSION['user']['role_data']['admin_id'];
            
            $stmt = $db->prepare("
                INSERT INTO banque_fond (montant, id_type_fond, id_agent)
                VALUES (?, ?, NULL)
            ");
            $stmt->execute([$montant, $typeFondId]);
            
            Flight::json([
                'success' => true,
                'message' => 'Fonds ajoutés avec succès'
            ]);
        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout des fonds'
            ]);
        }
    }
}
