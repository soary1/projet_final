<?php
require_once 'controllers/CalculController.php';
Flight::route('POST /calcul/simuler', [new CalculController(), 'simulate']);
