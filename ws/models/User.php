<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Authentifier un utilisateur
     */
    public function authenticate($email, $password) {
        try {
            $stmt = $this->db->prepare("
                SELECT u.id, u.nom, u.email, u.mot_de_passe,
                       a.id as agent_id, a.matricule,
                       ad.id as admin_id, ad.niveau_acces
                FROM banque_utilisateur u
                LEFT JOIN banque_agent a ON u.id = a.id_utilisateur
                LEFT JOIN banque_admin ad ON u.id = ad.id_utilisateur
                WHERE u.email = ?
            ");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && ($password === $user['mot_de_passe'])) {
                // Déterminer le type d'utilisateur
                if ($user['admin_id']) {
                    $user['type'] = 'admin';
                    $user['role_data'] = [
                        'admin_id' => $user['admin_id'],
                        'niveau_acces' => $user['niveau_acces']
                    ];
                } elseif ($user['agent_id']) {
                    $user['type'] = 'agent';
                    $user['role_data'] = [
                        'agent_id' => $user['agent_id'],
                        'matricule' => $user['matricule']
                    ];
                } else {
                    $user['type'] = 'client';
                }
                
                // Nettoyer les données sensibles
                unset($user['mot_de_passe']);
                return $user;
            }
            
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Récupérer un utilisateur par ID
     */
    public function getUserById($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT u.id, u.nom, u.email,
                       a.id as agent_id, a.matricule, a.date_embauche,
                       ad.id as admin_id, ad.niveau_acces, ad.date_creation
                FROM banque_utilisateur u
                LEFT JOIN banque_agent a ON u.id = a.id_utilisateur
                LEFT JOIN banque_admin ad ON u.id = ad.id_utilisateur
                WHERE u.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Créer un nouvel utilisateur agent
     */
    public function createAgent($nom, $email, $password, $matricule) {
        try {
            $this->db->beginTransaction();
            
            // Créer l'utilisateur
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("
                INSERT INTO banque_utilisateur (nom, email, mot_de_passe) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$nom, $email, $hashedPassword]);
            $userId = $this->db->lastInsertId();
            
            // Créer le profil agent
            $stmt = $this->db->prepare("
                INSERT INTO banque_agent (id_utilisateur, matricule, date_embauche) 
                VALUES (?, ?, CURDATE())
            ");
            $stmt->execute([$userId, $matricule]);
            
            $this->db->commit();
            return $userId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    /**
     * Créer un nouvel utilisateur admin
     */
    public function createAdmin($nom, $email, $password, $niveauAcces = 'standard') {
        try {
            $this->db->beginTransaction();
            
            // Créer l'utilisateur
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("
                INSERT INTO banque_utilisateur (nom, email, mot_de_passe) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$nom, $email, $hashedPassword]);
            $userId = $this->db->lastInsertId();
            
            // Créer le profil admin
            $stmt = $this->db->prepare("
                INSERT INTO banque_admin (id_utilisateur, niveau_acces) 
                VALUES (?, ?)
            ");
            $stmt->execute([$userId, $niveauAcces]);
            
            $this->db->commit();
            return $userId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
