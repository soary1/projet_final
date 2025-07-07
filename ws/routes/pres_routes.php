<?php
require_once __DIR__ . '/../controllers/PretController.php';

Flight::route('GET /prets',   ['PretController', 'getAllPrets']);
Flight::route('GET /prets/clients/@id',   ['PretController', 'listPretByClient']);
Flight::route('POST /prets/clients/@id',  ['PretController', 'createPret']);
Flight::route('DELETE /prets/@id',        ['PretController', 'deletePret']);
Flight::route('GET /prets', ['PretController', 'getAllPrets']);
