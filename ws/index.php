<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'vendor/autoload.php';
require 'db.php';
require 'routes/etudiant_routes.php';
require 'routes/client_routes.php';
require 'routes/pres_routes.php';
require 'routes/type_pret_route.php';

<<<<<<< Updated upstream

Flight::start();
=======
// Charger les routes
require 'routes/AuthRoutes.php';

Flight::start();
>>>>>>> Stashed changes
