<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Banque</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .main-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
      text-align: center;
    }

    .logo {
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 50%;
      margin: 0 auto 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 32px;
      font-weight: bold;
    }

    h1 {
      color: #333;
      margin-bottom: 10px;
      font-size: 32px;
      font-weight: 600;
    }

    .subtitle {
      color: #666;
      margin-bottom: 40px;
      font-size: 18px;
    }

    .login-options {
      display: grid;
      gap: 20px;
      margin-bottom: 30px;
    }

    .option-card {
      background: white;
      border: 2px solid #e0e0e0;
      border-radius: 15px;
      padding: 25px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      color: inherit;
    }

    .option-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      border-color: #667eea;
    }

    .option-card.client {
      border-color: #4facfe;
    }

    .option-card.client:hover {
      border-color: #4facfe;
      box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
    }

    .option-card.agent {
      border-color: #667eea;
    }

    .option-card.agent:hover {
      border-color: #667eea;
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .option-card.admin {
      border-color: #ff6b6b;
    }

    .option-card.admin:hover {
      border-color: #ff6b6b;
      box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
    }

    .option-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      margin: 0 auto 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: white;
    }

    .option-card.client .option-icon {
      background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .option-card.agent .option-icon {
      background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .option-card.admin .option-icon {
      background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    }

    .option-title {
      font-size: 20px;
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
    }

    .option-description {
      color: #666;
      font-size: 14px;
      line-height: 1.4;
    }

    .footer-text {
      color: #999;
      font-size: 14px;
      margin-top: 20px;
    }

    @media (max-width: 600px) {
      .main-container {
        padding: 30px 20px;
        margin: 10px;
      }
      
      h1 {
        font-size: 28px;
      }
      
      .subtitle {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>
  <div class="main-container">
    <div class="logo">🏦</div>
    <h1>Banque</h1>
    <p class="subtitle">Choisissez votre type de connexion</p>
    
    <div class="login-options">
      <a href="#" class="option-card client" onclick="loginAsClient()">
        <div class="option-icon">👤</div>
        <div class="option-title">Client</div>
        <div class="option-description">
          Accédez à votre espace personnel pour gérer vos comptes et demandes de prêts
        </div>
      </a>
      
      <a href="agents/login.php" class="option-card agent">
        <div class="option-icon">👔</div>
        <div class="option-title">Agent</div>
        <div class="option-description">
          Interface dédiée aux agents pour traiter les demandes et gérer les fonds
        </div>
      </a>
      
      <a href="admin/login.php" class="option-card admin">
        <div class="option-icon">⚡</div>
        <div class="option-title">Administrateur</div>
        <div class="option-description">
          Accès sécurisé pour l'administration et la supervision du système
        </div>
      </a>
    </div>
    
    <div class="footer-text">
      Système bancaire sécurisé - Version 1.0
    </div>
  </div>

  <script>
    function loginAsClient() {
      alert('Interface client en cours de développement. Veuillez utiliser l\'interface Agent ou Administrateur pour le moment.');
    }
  </script>
</body>
</html>
