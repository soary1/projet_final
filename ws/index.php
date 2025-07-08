<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'vendor/autoload.php';
require 'db.php';
require 'routes/interets_routes.php';

require 'routes/client_routes.php';
require 'routes/pres_routes.php';
require 'routes/type_pret_route.php';
require 'routes/calcul_routes.php';
require 'routes/remboursement_route.php';
require 'routes/utilisateur_routes.php';
require 'routes/remboursement_routes.php';


Flight::start();
