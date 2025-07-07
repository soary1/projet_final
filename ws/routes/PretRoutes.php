<?php

require_once __DIR__ . '/../controllers/PretController.php';

Flight::route('GET /prets/en-attente', ['PretController', 'getEnAttente']);
Flight::route('POST /pret/valider/@id', ['PretController', 'valider']);
Flight::route('POST /pret/refuser/@id', ['PretController', 'refuser']);
Flight::route('GET /typefond', ['PretController', 'getTypesFond']);
Flight::route('POST /fond', ['PretController', 'ajouterFond']);

