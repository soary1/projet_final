<?php
require_once 'controllers/AuthController.php';

class AgentController {
    
    public function __construct() {
        // Le constructeur ne vérifie plus l'authentification
        // Chaque méthode qui en a besoin la vérifiera individuellement
    }
    
    /**
     * Afficher le dashboard des agents
     */
    public function dashboard() {
        if (!AuthController::requireAgent()) {
            return;
        }
        $user = $_SESSION['user'];
        include '../views/agents/dashboard.php';
    }
    
    /**
     * Récupérer les statistiques pour le dashboard
     */
    public function getStats() {
        if (!AuthController::requireAgent()) {
            return;
        }
        
        try {
            $db = getDB();
            $agentId = $_SESSION['user']['role_data']['agent_id'];
            
            // Nombre de prêts traités
            $stmt = $db->prepare("
                SELECT COUNT(*) as total_prets,
                       SUM(CASE WHEN statut = 'approuvé' THEN 1 ELSE 0 END) as prets_approuves,
                       SUM(CASE WHEN statut = 'en attente' THEN 1 ELSE 0 END) as prets_en_attente,
                       SUM(CASE WHEN statut = 'rejeté' THEN 1 ELSE 0 END) as prets_rejetes
                FROM banque_pret 
                WHERE id_agent = ?
            ");
            $stmt->execute([$agentId]);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Montant total des prêts
            $stmt = $db->prepare("
                SELECT SUM(montant) as montant_total 
                FROM banque_pret 
                WHERE id_agent = ? AND statut = 'approuvé'
            ");
            $stmt->execute([$agentId]);
            $montant = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['montant_total'] = $montant['montant_total'] ?? 0;
            
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
     * Récupérer les prêts de l'agent
     */
    public function getPrets() {
        if (!AuthController::requireAgent()) {
            return;
        }
        
        try {
            $db = getDB();
            $agentId = $_SESSION['user']['role_data']['agent_id'];
            
            $stmt = $db->prepare("
                SELECT p.id, p.montant, p.date_demande, p.statut,
                       u.nom as client_nom, u.email as client_email,
                       tp.nom as type_pret, tp.taux_interet, tp.duree_mois
                FROM banque_pret p
                JOIN banque_client c ON p.id_client = c.id
                JOIN banque_utilisateur u ON c.id_utilisateur = u.id
                JOIN banque_type_pret tp ON p.id_type_pret = tp.id
                WHERE p.id_agent = ?
                ORDER BY p.date_demande DESC
            ");
            $stmt->execute([$agentId]);
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
     * Mettre à jour le statut d'un prêt
     */
    public function updatePretStatus() {
        if (!AuthController::requireAgent()) {
            return;
        }
        
        $pretId = $_POST['pret_id'] ?? '';
        $nouveauStatut = $_POST['statut'] ?? '';

        if (empty($pretId) || empty($nouveauStatut)) {
            Flight::json([
                'success' => false,
                'message' => 'Données manquantes'
            ]);
            return;
        }
        
        try {
            $db = getDB();
            $agentId = $_SESSION['user']['role_data']['agent_id'];
            
            $db->beginTransaction();
            
            // Récupérer l'ancien statut
            $stmt = $db->prepare("
                SELECT statut FROM banque_pret 
                WHERE id = ? AND id_agent = ?
            ");
            $stmt->execute([$pretId, $agentId]);
            $pret = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$pret) {
                throw new Exception('Prêt non trouvé');
            }
            
            $ancienStatut = $pret['statut'];
            
            // Mettre à jour le statut
            $stmt = $db->prepare("
                UPDATE banque_pret 
                SET statut = ? 
                WHERE id = ? AND id_agent = ?
            ");
            $stmt->execute([$nouveauStatut, $pretId, $agentId]);
            
            // Enregistrer dans l'historique
            $stmt = $db->prepare("
                INSERT INTO banque_historique_pret (id_pret, ancien_statut, nouveau_statut)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$pretId, $ancienStatut, $nouveauStatut]);
            
            $db->commit();
            
            Flight::json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès'
            ]);
        } catch (Exception $e) {
            $db->rollBack();
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ]);
        }
    }
}
