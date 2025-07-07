<?php
require_once __DIR__ . '/../controllers/PretController.php';
require_once __DIR__ . '/../controllers/SimulationController.php';

Flight::route('GET /prets',   ['PretController', 'getAllPrets']);
Flight::route('GET /prets/clients/@id',   ['PretController', 'listPretByClient']);
Flight::route('POST /prets/clients/@id',  ['PretController', 'createPret']);
Flight::route('DELETE /prets/@id',        ['PretController', 'deletePret']);


Flight::route('GET /prets/en-attente', ['PretController', 'getEnAttente']);
Flight::route('POST /pret/valider/@id', ['PretController', 'valider']);
Flight::route('POST /pret/refuser/@id', ['PretController', 'refuser']);
Flight::route('GET /typefond', ['PretController', 'getTypesFond']);
Flight::route('POST /fond', ['PretController', 'ajouterFond']);
Flight::route('GET /clients-agents', ['SimulationController', 'getClientsEtAgents']);
Flight::route('POST /simulation/valider', ['SimulationController', 'simulerPret']);
Flight::route('POST /typepret', ['PretController', 'ajouterType']);

