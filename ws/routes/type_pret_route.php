<?php
require_once __DIR__ . '/../controllers/TypePretController.php';

Flight::route('GET  /typepret',              ['TypePretController', 'getAllTypesPret']);
Flight::route('GET  /typepret/@id:[0-9]+',   ['TypePretController', 'getTypePretById']);
Flight::route('POST /typepret',              ['TypePretController', 'createTypePret']);

