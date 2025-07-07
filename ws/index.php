<?php

require 'vendor/autoload.php';
require 'db.php';
require 'routes/interets_routes.php';

require 'routes/client_routes.php';
require 'routes/pres_routes.php';
require 'routes/type_pret_route.php';
require 'routes/utilisateur_routes.php';

Flight::start();