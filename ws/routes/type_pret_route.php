<?php
require_once __DIR__ . '/../controllers/TypePretController.php';

Flight::route('GET /typepret',          ['TypePretController', 'getAllTypesPret']);
Flight::route('POST /typepret',         ['TypePretController', 'createTypePret']);
Flight::route('GET /typepret/@id', ['TypePretController', 'getTypePretById']);
