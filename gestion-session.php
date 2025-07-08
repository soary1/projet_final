<?php
session_start();

if (!isset($_POST['role'])) {
  header("Location: login.php?erreur=Aucune donnée reçue");
  exit;
}

// Stocker les infos reçues dans la session
$_SESSION['user']['id'] = $_POST['id'] ?? null;
$_SESSION['user']['nom'] = $_POST['nom'] ?? '';
$_SESSION['user']['email'] = $_POST['email'] ?? '';
$_SESSION['user']['role'] = $_POST['role'] ?? '';
$_SESSION['user']['matricule'] = $_POST['matricule'] ?? null;
$_SESSION['user']['profession'] = $_POST['profession'] ?? null;

// Redirection selon le rôle
switch ($_SESSION['role']) {
  case 'admin':
    header('Location: views/admin/accueil.php');
    break;
  case 'agent':
    header('Location: views/agents/accueil.html');
    break;
  case 'client':
    header('Location: views/client/accueil.html');
    break;
  default:
    header('Location: login.php?erreur=Rôle inconnu');
    break;
}
exit;
