<?php

require_once __DIR__ . '/../controllers/AuthController.php';

Flight::route('POST /connexion', ['AuthController', 'login']);
Flight::route('POST /session', ['AuthController', 'createSession']);
Flight::route('GET /session_user', ['AuthController', 'getSessionUser']);
Flight::route('GET /logout', ['AuthController', 'logout']);
Flight::route('POST /logout', ['AuthController', 'logout']);

