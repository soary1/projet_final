# Test des systèmes de login

## URLs pour tester :

### Agents
- Login page: http://localhost/projet_final/ws/agent/login
- Dashboard: http://localhost/projet_final/ws/agent/dashboard

### Admins  
- Login page: http://localhost/projet_final/ws/admin/login
- Dashboard: http://localhost/projet_final/ws/admin/dashboard

## Comptes de test

### Agent
- Email: agent@banque.com
- Mot de passe: password

### Admin
- Email: admin@banque.com  
- Mot de passe: password

## Structure créée

### Models
- `ws/models/User.php` - Gestion des utilisateurs et authentification

### Controllers
- `ws/controllers/AuthController.php` - Gestion de l'authentification
- `ws/controllers/AgentController.php` - Fonctionnalités pour les agents
- `ws/controllers/AdminController.php` - Fonctionnalités pour les admins

### Routes
- `ws/routes/AuthRoutes.php` - Toutes les routes d'authentification et API

### Views
- `views/agents/login.php` - Page de login des agents (mise à jour)
- `views/agents/dashboard.php` - Dashboard des agents (nouveau)
- `views/admin/login.php` - Page de login des admins (mise à jour)
- `views/admin/dashboard.php` - Dashboard des admins (nouveau)

## Fonctionnalités

### Pour les agents
- Connexion sécurisée
- Dashboard avec statistiques personnelles
- Gestion des prêts assignés
- Approbation/rejet des prêts
- Historique des actions

### Pour les admins
- Connexion sécurisée
- Dashboard global avec statistiques
- Gestion des agents
- Vue globale des prêts
- Gestion des types de prêts
- Gestion des fonds

## Sécurité
- Mots de passe hashés avec password_hash()
- Vérification des sessions
- Contrôle d'accès par rôle
- Protection CSRF potentielle
