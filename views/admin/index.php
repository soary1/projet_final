<?php
// filepath: c:\xampp\htdocs\projet_final\views\admin\index.php
session_start();

$message = '';
$message_type = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $message = 'Veuillez remplir tous les champs';
        $message_type = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Veuillez entrer une adresse email valide';
        $message_type = 'error';
    } else {
        // Appel à l'API pour la connexion
        $donnees = json_encode([
            'email' => $email,
            'mot_de_passe' => $password
        ]);
        
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => $donnees
            ]
        ]);
        
        $response = @file_get_contents('http://localhost/projet_final/ws/connexion', false, $context);
        
        if ($response !== false) {
            $data = json_decode($response, true);
            
            if ($data['succes'] && $data['role'] === 'admin') {
                // Création de la session
                $_SESSION['utilisateur'] = [
                    'id' => $data['id'],
                    'email' => $email,
                    'nom' => $data['nom'],
                    'role' => 'admin',
                    'niveau_acces' => $data['niveau_acces'] ?? 'standard',
                    'connecte' => true,
                    'derniere_connexion' => date('Y-m-d H:i:s')
                ];
                
                header('Location: admin.php');
                exit();
            } elseif ($data['succes'] && $data['role'] !== 'admin') {
                $message = 'Accès refusé. Vous n\'avez pas les droits administrateur.';
                $message_type = 'error';
            } else {
                $message = 'Email ou mot de passe incorrect';
                $message_type = 'error';
            }
        } else {
            $message = 'Erreur de connexion. Vérifiez vos identifiants.';
            $message_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion Admin - Banque</title>
  <style>
  </style>
</head>
<body>

  <div class="login-container">
    <div class="login-header">
      <div class="bank-logo">🏦</div>
      <h1>Connexion Administrateur</h1>
      <div class="admin-badge">🔐 ACCÈS SÉCURISÉ</div>
      <p>Interface d'administration bancaire</p>
    </div>

    <?php if (!empty($message)): ?>
    <div class="message <?php echo htmlspecialchars($message_type); ?>" style="display: block;">
      <?php echo htmlspecialchars($message); ?>
    </div>
    <?php endif; ?>

    <form id="loginForm" method="POST" action="">
      <div class="form-group">
        <label for="email">Adresse email administrateur :</label>
        <input type="email" id="email" name="email" required placeholder="admin@banque.com" 
               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
      </div>

      <div class="form-group">
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required placeholder="••••••••">
      </div>

      <button type="submit" class="login-button">
        🔓 Accéder à l'administration
      </button>
    </form>

  </div>

  <script>
    const apiBase = "http://localhost/projet_final/ws";

    function afficherMessage(texte, type) {
      const messageEl = document.getElementById('message');
      if (messageEl) {
        messageEl.textContent = texte;
        messageEl.className = `message ${type}`;
        messageEl.style.display = 'block';

        setTimeout(() => {
          messageEl.style.display = 'none';
        }, 5000);
      }
    }

    // Masquer automatiquement les messages PHP après 5 secondes
    document.addEventListener('DOMContentLoaded', function() {
      const messageEl = document.querySelector('.message');
      if (messageEl && messageEl.style.display !== 'none') {
        setTimeout(() => {
          messageEl.style.display = 'none';
        }, 5000);
      }
    });

    // Fonction de déconnexion automatique après inactivité (30 minutes pour admin)
    let inactivityTimer;
    function resetInactivityTimer() {
      clearTimeout(inactivityTimer);
      inactivityTimer = setTimeout(() => {
        window.location.href = '../logout.php';
      }, 30 * 60 * 1000); // 30 minutes
    }

    // Réinitialiser le timer sur activité
    document.addEventListener('mousemove', resetInactivityTimer);
    document.addEventListener('keypress', resetInactivityTimer);
    
    // Démarrer le timer
    resetInactivityTimer();
  </script>