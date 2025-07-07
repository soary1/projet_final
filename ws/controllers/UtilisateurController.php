<?php
require_once __DIR__ . '/../models/Utilisateur.php';

class UtilisateurController
{
    public static function all()         { Flight::json(Utilisateur::all()); }
    public static function one(int $id)  {  }
    public static function create()      {  }
    public static function update(int $id){  }
    public static function delete(int $id){  }
}
