<?php
require_once __DIR__ . '/../controllers/SimulationController.php';
Flight::route('POST /simulation/valider', ['SimulationController', 'simulerPret']);
Flight::route('GET /types-pret', ['SimulationController', 'getTypesPret']);
Flight::route('/comparaison', ['SimulationController', 'afficherComparaison']);

Flight::route('GET /simulations', ['SimulationController', 'lister']);
// Lier la route GET pour comparer les simulations
Flight::route('/comparaison', ['SimulationController', 'afficherComparaison']);
