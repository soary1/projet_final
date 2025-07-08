<?php
session_start();

// // ⚠️ Sécurité : redirige si l'agent n'est pas connecté
// if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'agent') {
//     header("Location: /projet_final/views/agents/login.php");
//     exit;
// }

// $id_agent = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un Prêt - EF Mada</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --warning-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
      --dark-bg: #0f1419;
      --card-bg: #1a1f2e;
      --sidebar-bg: #161b22;
      --text-primary: #ffffff;
      --text-secondary: #8b949e;
      --border-color: #30363d;
      --hover-bg: #21262d;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: var(--dark-bg);
      color: var(--text-primary);
      line-height: 1.6;
      overflow-x: hidden;
    }

    .sidebar {
      height: 100vh;
      background: var(--sidebar-bg);
      backdrop-filter: blur(10px);
      padding: 2rem 0;
      position: fixed;
      width: 280px;
      border-right: 1px solid var(--border-color);
      z-index: 1000;
    }

    .sidebar-header {
      padding: 0 2rem 2rem;
      border-bottom: 1px solid var(--border-color);
      margin-bottom: 2rem;
    }

    .sidebar-header h2 {
      font-size: 1.5rem;
      font-weight: 700;
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .sidebar-nav {
      padding: 0 1rem;
    }

    .sidebar-nav a {
      display: flex;
      align-items: center;
      gap: 1rem;
      color: var(--text-secondary);
      text-decoration: none;
      padding: 1rem 1.5rem;
      border-radius: 12px;
      margin-bottom: 0.5rem;
      transition: all 0.3s ease;
      font-weight: 500;
      position: relative;
      overflow: hidden;
    }

    .sidebar-nav a::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: var(--primary-gradient);
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: -1;
    }

    .sidebar-nav a:hover {
      color: var(--text-primary);
      transform: translateX(4px);
    }

    .sidebar-nav a:hover::before {
      opacity: 0.1;
    }

    .sidebar-nav a.active {
      color: var(--text-primary);
      background: var(--hover-bg);
      border-left: 3px solid #667eea;
    }

    .main-content {
      margin-left: 280px;
      padding: 2rem;
      min-height: 100vh;
      background: var(--dark-bg);
    }

    .page-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border-color);
    }

    .page-header h1 {
      font-size: 2rem;
      font-weight: 700;
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .page-header .subtitle {
      color: var(--text-secondary);
      font-size: 0.95rem;
    }

    .card-modern {
      background: var(--card-bg);
      border-radius: 16px;
      padding: 2rem;
      margin-bottom: 2rem;
      border: 1px solid var(--border-color);
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
      position: relative;
      overflow: hidden;
    }

    .card-modern::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: var(--primary-gradient);
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .form-group label {
      color: var(--text-secondary);
      font-weight: 500;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .form-control-modern {
      background: var(--hover-bg);
      border: 2px solid var(--border-color);
      border-radius: 12px;
      padding: 1rem;
      color: var(--text-primary);
      transition: all 0.3s ease;
      font-size: 1rem;
      position: relative;
    }

    .form-control-modern:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control-modern option {
      background: var(--card-bg);
      color: var(--text-primary);
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      font-size: 1.1rem;
      z-index: 10;
    }

    .form-control-modern.with-icon {
      padding-left: 3rem;
    }

    .btn-primary {
      background: var(--primary-gradient);
      border: none;
      color: white;
      padding: 1rem 2rem;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      justify-content: center;
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s ease;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover::before {
      left: 100%;
    }

    .btn-primary:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .btn-secondary {
      background: var(--hover-bg);
      border: 2px solid var(--border-color);
      color: var(--text-primary);
      padding: 1rem 2rem;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      justify-content: center;
    }

    .btn-secondary:hover {
      background: var(--border-color);
      color: var(--text-primary);
    }

    .button-group {
      display: flex;
      gap: 1rem;
      justify-content: flex-end;
      margin-top: 2rem;
    }

    .loading-spinner {
      display: none;
      margin-right: 0.5rem;
    }

    .spinner {
      width: 20px;
      height: 20px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top: 2px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .alert {
      padding: 1rem;
      border-radius: 12px;
      margin-bottom: 1rem;
      display: none;
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-success {
      background: rgba(79, 172, 254, 0.1);
      color: #4facfe;
      border: 1px solid rgba(79, 172, 254, 0.3);
    }

    .alert-error {
      background: rgba(255, 107, 107, 0.1);
      color: #ff6b6b;
      border: 1px solid rgba(255, 107, 107, 0.3);
    }

    .form-help {
      color: var(--text-secondary);
      font-size: 0.85rem;
      margin-top: 0.25rem;
    }

    .required {
      color: #ff6b6b;
    }

    .stats-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: var(--card-bg);
      border-radius: 12px;
      padding: 1.5rem;
      border: 1px solid var(--border-color);
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: var(--primary-gradient);
    }

    .stat-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .stat-label {
      color: var(--text-secondary);
      font-size: 0.9rem;
      font-weight: 500;
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }
      
      .main-content {
        margin-left: 0;
        padding: 1rem;
      }
      
      .form-grid {
        grid-template-columns: 1fr;
      }
      
      .button-group {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="sidebar-header">
      <h2><i class="bi bi-graph-up"></i> EF Mada</h2>
    </div>
    <nav class="sidebar-nav">
      <a href="liste_prets.html"><i class="bi bi-card-list"></i> Voir les prêts</a>
      <a href="prets-attente.html"><i class="bi bi-clock-history"></i> Prêts en attente</a>
      <a href="fond.php"><i class="bi bi-piggy-bank"></i> Ajouter un fond</a>
      <a href="simulation.php"><i class="bi bi-calculator"></i> Simulation Prêt</a>
      <a href="interet.php"><i class="bi bi-bar-chart"></i> Intérêts</a>
      <a href="#" class="active"><i class="bi bi-plus-circle"></i> Ajouter un Prêt</a>
      <a href="#" onclick="logout()"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </nav>
  </div>

  <div class="main-content">
    <div class="page-header">
      <div class="icon">
        <i class="bi bi-plus-circle" style="font-size: 1.5rem;"></i>
      </div>
      <div>
        <h1>Ajouter un Prêt</h1>
        <p class="subtitle">Créer une nouvelle demande de prêt</p>
      </div>
    </div>

    <div class="stats-row" id="stats-row">
      <div class="stat-card">
        <div class="stat-value" id="total-clients">-</div>
        <div class="stat-label">Clients disponibles</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="total-types">-</div>
        <div class="stat-label">Types de prêts</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">Agent</div>
        <div class="stat-label">Votre rôle</div>
      </div>
    </div>

    <div class="alert" id="alert-message"></div>

    <div class="card-modern">
      <div class="page-header">
        <div class="icon">
          <i class="bi bi-file-earmark-plus" style="font-size: 1.5rem;"></i>
        </div>
        <div>
          <h3>Informations du prêt</h3>
          <p class="subtitle">Remplissez tous les champs requis</p>
        </div>
      </div>

      <form id="loan-form">
        <div class="form-grid">
          <div class="form-group">
            <label for="id_client">
              <i class="bi bi-person"></i>
              Client <span class="required">*</span>
            </label>
            <select id="id_client" class="form-control-modern" required>
              <option value="">-- Choisir un client --</option>
            </select>
            <div class="form-help">Sélectionnez le client pour ce prêt</div>
          </div>

          <div class="form-group">
            <label for="id_type_pret">
              <i class="bi bi-tags"></i>
              Type de prêt <span class="required">*</span>
            </label>
            <select id="id_type_pret" class="form-control-modern" required>
              <option value="">-- Choisir un type --</option>
            </select>
            <div class="form-help">Type de prêt à accorder</div>
          </div>

          <div class="form-group">
            <label for="montant">
              <i class="bi bi-cash"></i>
              Montant <span class="required">*</span>
            </label>
            <div class="input-wrapper">
              <i class="input-icon bi bi-currency-exchange"></i>
              <input 
                type="number" 
                id="montant" 
                class="form-control-modern with-icon" 
                placeholder="0.00"
                min="0"
                step="0.01"
                required
              >
            </div>
            <div class="form-help">Montant du prêt en Ariary</div>
          </div>
        </div>

        <div class="button-group">
          <a href="liste_prets.html" class="btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Retour à la liste
          </a>
          <button type="submit" class="btn-primary" id="submit-btn">
            <span class="loading-spinner" id="loading">
              <div class="spinner"></div>
            </span>
            <i class="bi bi-check-circle"></i>
            Soumettre le prêt
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const apiBase = "http://localhost/projet_final/ws";
    
    const idAgent = 1;


    function showAlert(message, type = 'success') {
      const alert = document.getElementById('alert-message');
      alert.className = `alert alert-${type}`;
      alert.innerHTML = `
        <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'}"></i>
        ${message}
      `;
      alert.style.display = 'block';
      
      if (type === 'success') {
        setTimeout(() => {
          alert.style.display = 'none';
        }, 5000);
      }
    }

    function showLoading() {
      const btn = document.getElementById('submit-btn');
      const loading = document.getElementById('loading');
      
      btn.disabled = true;
      loading.style.display = 'inline-block';
      btn.innerHTML = '<span class="loading-spinner" style="display: inline-block;"><div class="spinner"></div></span> Traitement en cours...';
    }

    function hideLoading() {
      const btn = document.getElementById('submit-btn');
      const loading = document.getElementById('loading');
      
      btn.disabled = false;
      loading.style.display = 'none';
      btn.innerHTML = '<i class="bi bi-check-circle"></i> Soumettre le prêt';
    }

    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            try {
              const response = JSON.parse(xhr.responseText);
              callback && callback(response);
            } catch (e) {
              showAlert("Erreur de format de réponse", "error");
            }
          } else {
            showAlert(`Erreur serveur: ${xhr.status}`, "error");
          }
        }
      };
      xhr.send(data);
    }

    function chargerClients() {
      ajax("GET", "/clients", null, (data) => {
        const select = document.getElementById("id_client");
        select.innerHTML = '<option value="">-- Choisir un client --</option>';
        
        data.forEach(c => {
          const opt = document.createElement("option");
          opt.value = c.id;
          opt.textContent = `${c.nom_utilisateur} (ID: ${c.id})`;
          select.appendChild(opt);
        });
        
        document.getElementById('total-clients').textContent = data.length;
      });
    }

    function chargerTypesPret() {
      ajax("GET", "/typepret", null, (data) => {
        const select = document.getElementById("id_type_pret");
        select.innerHTML = '<option value="">-- Choisir un type --</option>';
        
        data.forEach(t => {
          const opt = document.createElement("option");
          opt.value = t.id;
          opt.textContent = t.nom;
          select.appendChild(opt);
        });
        
        document.getElementById('total-types').textContent = data.length;
      });
    }

   function ajouterPret() {
  const id_client = document.getElementById("id_client").value;
  const id_type_pret = document.getElementById("id_type_pret").value;
  const montant = document.getElementById("montant").value;

  if (!id_client || !id_type_pret || !montant) {
    showAlert("Veuillez remplir tous les champs requis.", "error");
    return;
  }

  if (parseFloat(montant) <= 0) {
    showAlert("Le montant doit être supérieur à 0.", "error");
    return;
  }

  showLoading();

  const data = {
    id_client: id_client,
    id_agent: 1, // ou remplace par ta variable idAgent si elle est globale
    id_type_pret: id_type_pret,
    montant: montant
  };

  fetch(`${apiBase}/prets/clients/${id_client}`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  })
    .then(res => res.json())
    .then(response => {
      // hideLoading();

      if (response.success || response.id || response.message) {
        showAlert("Prêt soumis avec succès !", "success");

        // Reset form
        document.getElementById("id_client").value = "";
        document.getElementById("id_type_pret").value = "";
        document.getElementById("montant").value = "";

        setTimeout(() => {
          window.location.href = "liste_prets.html";
        }, 2000);
      } else {
        showAlert(response.error || "Erreur lors de l'ajout du prêt", "error");
      }
    })
    .catch(err => {
      console.error(err);
      // hideLoading();
      showAlert("Erreur réseau ou serveur", "error");
    });
}

 // Form submission handler
    document.getElementById('loan-form').addEventListener('submit', function(e) {
      e.preventDefault();
      ajouterPret();
    });

    // Format montant input
    document.getElementById('montant').addEventListener('input', function(e) {
      const value = parseFloat(e.target.value);
      if (!isNaN(value)) {
        e.target.style.color = 'var(--text-primary)';
      }
    });

    // Load data on page load
    window.addEventListener('load', function() {
      chargerClients();
      chargerTypesPret();
    });

    function logout() {
      fetch(`${apiBase}/logout`, {
        method: "POST"
      }).then(() => {
        window.location.href = "/projet_final/views/agents/login.php";
      });
    }
  </script>
</body>
</html>