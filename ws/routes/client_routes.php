<?php
require_once __DIR__ . '/../controllers/ClientController.php';


/* -------------- CLIENTS -------------------- */
Flight::route('GET /clients',             ['ClientController', 'getAllClients']);
Flight::route('POST /clients',            ['ClientController', 'createClient']);
Flight::route('DELETE /clients/@id',      ['ClientController', 'deleteClient']);

/* ------------- TYPES DE PRÊT --------------- */
Flight::route('GET /types-pret',          ['TypePretController', 'getAllTypesPret']);
Flight::route('POST /types-pret',         ['TypePretController', 'createTypePret']);

/* ----------------- PRÊTS ------------------- */
Flight::route('GET /clients/@id/prets',   ['Pret', 'listPretByClient']);
Flight::route('POST /clients/@id/prets',  ['Pret', 'createPret']);
Flight::route('DELETE /prets/@id',        ['Pret', 'deletePret']);