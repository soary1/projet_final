<?php
    require_once __DIR__ . '/../controllers/InteretController.php';
    Flight::route('GET /interets', ['InteretController', 'getInterets']);
    // Flight::route('GET /interets-reels', ['InteretController', 'interetsReels']);
    Flight::route('GET /interets-par-mois', ['InteretController', 'interetsParMois']);



