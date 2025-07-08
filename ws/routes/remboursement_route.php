<?php
require_once __DIR__ . '/../controllers/RemboursementController.php';
Flight::route('GET /remboursements', [new RemboursementController(), 'getAll']);
Flight::route('GET /remboursements/@idPret', [new RemboursementController(), 'getByPret']);
Flight::route('POST /remboursements', [new RemboursementController(), 'create']);
Flight::route('DELETE /remboursements/@id', [new RemboursementController(), 'delete']);
Flight::route('GET /retards', [new RemboursementController(), 'getRetards']);
Flight::route('POST /remboursements/tout/@idPret', [new RemboursementController(), 'rembourserTout']);

