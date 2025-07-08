<?php

session_start();

require_once 'controllers/AuthController.php';
require_once 'controllers/AgentController.php';
require_once 'controllers/AdminController.php';

// Instanciation des controllers
$authController = new AuthController();
$agentController = new AgentController();
$adminController = new AdminController();

// Routes pour les agents
Flight::route('GET /agent/login', [$authController, 'showAgentLogin']);
Flight::route('POST /agent/login', [$authController, 'loginAgent']);
Flight::route('GET /agent/dashboard', [$agentController, 'dashboard']);
Flight::route('GET /agent/stats', [$agentController, 'getStats']);
Flight::route('GET /agent/prets', [$agentController, 'getPrets']);
Flight::route('POST /agent/pret/update-status', [$agentController, 'updatePretStatus']);

// Routes pour les admins
Flight::route('GET /admin/login', [$authController, 'showAdminLogin']);
Flight::route('POST /admin/login', [$authController, 'loginAdmin']);
Flight::route('GET /admin/dashboard', [$adminController, 'dashboard']);
Flight::route('GET /admin/stats', [$adminController, 'getGlobalStats']);
Flight::route('GET /admin/agents', [$adminController, 'getAgents']);
Flight::route('POST /admin/agents', [$adminController, 'createAgent']);
Flight::route('GET /admin/prets', [$adminController, 'getAllPrets']);
Flight::route('GET /admin/types-prets', [$adminController, 'getTypesPrets']);
Flight::route('POST /admin/types-prets', [$adminController, 'createTypePret']);
Flight::route('POST /admin/fonds', [$adminController, 'addFonds']);

Flight::route('GET /logout', [$authController, 'logout']);
Flight::route('POST /logout', [$authController, 'logout']);

Flight::route('/', function() {
    Flight::redirect('/agent/login');
});


require_once 'controllers/AuthController.php';
require_once 'controllers/AgentController.php';
require_once 'controllers/AdminController.php';

$authController = new AuthController();
$agentController = new AgentController();
$adminController = new AdminController();

// Agents
Flight::route('GET /agent/login', [$authController, 'showAgentLogin']);
Flight::route('POST /agent/login', [$authController, 'loginAgent']);
Flight::route('GET /agent/dashboard', [$agentController, 'dashboard']);
Flight::route('GET /agent/stats', [$agentController, 'getStats']);
Flight::route('GET /agent/prets', [$agentController, 'getPrets']);
Flight::route('POST /agent/pret/update-status', [$agentController, 'updatePretStatus']);

// Admins
Flight::route('GET /admin/login', [$authController, 'showAdminLogin']);
Flight::route('POST /admin/login', [$authController, 'loginAdmin']);
Flight::route('GET /admin/dashboard', [$adminController, 'dashboard']);
Flight::route('GET /admin/stats', [$adminController, 'getGlobalStats']);
Flight::route('GET /admin/agents', [$adminController, 'getAgents']);
Flight::route('POST /admin/agents', [$adminController, 'createAgent']);
Flight::route('GET /admin/prets', [$adminController, 'getAllPrets']);
Flight::route('GET /admin/types-prets', [$adminController, 'getTypesPrets']);
Flight::route('POST /admin/types-prets', [$adminController, 'createTypePret']);
Flight::route('POST /admin/fonds', [$adminController, 'addFonds']);

// Déconnexion



// Page d'accueil par défaut
Flight::route('/', function() {
    Flight::redirect('/agent/login');
});
