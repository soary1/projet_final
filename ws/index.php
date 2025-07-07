<?php
require 'vendor/autoload.php';
require 'db.php';

/* ----------- Charger le contrôleur ----------- */
require_once __DIR__ . '/../controller/BanqueController.php';

/* -------------- CLIENTS -------------------- */
Flight::route('GET /clients',             ['BanqueController', 'getAllClients']);
Flight::route('POST /clients',            ['BanqueController', 'createClient']);
Flight::route('DELETE /clients/@id',      ['BanqueController', 'deleteClient']);

/* ------------- TYPES DE PRÊT --------------- */
Flight::route('GET /types-pret',          ['BanqueController', 'getAllTypesPret']);
Flight::route('POST /types-pret',         ['BanqueController', 'createTypePret']);

/* ----------------- PRÊTS ------------------- */
Flight::route('GET /clients/@id/prets',   ['BanqueController', 'listPretByClient']);
Flight::route('POST /clients/@id/prets',  ['BanqueController', 'createPret']);
Flight::route('DELETE /prets/@id',        ['BanqueController', 'deletePret']);

Flight::start();
