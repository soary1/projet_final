<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un fond - Agent</title>
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
      --success-color: #4facfe;
      --warning-color: #fcb69f;
      --error-color: #ff6b6b;
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
      max-width: 600px;
      margin: 0 auto;
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

    .card-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .card-header h3 {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
    }

    .card-header .icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: var(--primary-gradient);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
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
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 0.75rem 1rem;
      color: var(--text-primary);
      transition: all 0.3s ease;
      font-size: 0.95rem;
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

    .btn-primary {
      background: var(--primary-gradient);
      border: none;
      color: white;
      padding: 0.75rem 2rem;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      justify-content: center;
      width: 100%;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 1rem 1.5rem;
      border-radius: 8px;
      color: white;
      font-weight: 500;
      z-index: 2000;
      transform: translateX(100%);
      transition: transform 0.3s ease;
    }

    .notification.success {
      background: var(--success-gradient);
    }

    .notification.error {
      background: var(--secondary-gradient);
    }

    .notification.show {
      transform: translateX(0);
    }

    .loading {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .loading::before {
      content: '';
      width: 16px;
      height: 16px;
      border: 2px solid transparent;
      border-top: 2px solid currentColor;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .agent-info {
      background: var(--hover-bg);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      border-left: 3px solid #667eea;
    }

    .agent-info .label {
      color: var(--text-secondary);
      font-size: 0.85rem;
      margin-bottom: 0.25rem;
    }

    .agent-info .value {
      color: var(--text-primary);
      font-weight: 600;
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
      
      .card-modern {
        max-width: 100%;
        margin: 0;
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
      <a href="#"><i class="bi bi-house-door"></i> Dashboard</a>
      <a href="#"><i class="bi bi-person"></i> Clients</a>
      <a href="#"><i class="bi bi-bar-chart"></i> Intérêts</a>
      <a href="#"><i class="bi bi-calculator"></i> Simulation</a>
      <a href="#" class="active"><i class="bi bi-wallet2"></i> Ajouter Fond</a>
      <a href="#"><i class="bi bi-gear"></i> Paramètres</a>
    </nav>
  </div>

  <div class="main-content">
    <div class="page-header">
      <div class="icon">
        <i class="bi bi-wallet2" style="font-size: 1.5rem;"></i>
      </div>
      <div>
        <h1>Ajouter un Fond</h1>
        <p class="subtitle">Gérez vos fonds et investissements</p>
      </div>
    </div>

    <div class="card-modern">
      <div class="card-header">
        <div class="icon">
          <i class="bi bi-plus-circle"></i>
        </div>
        <h3>Nouveau Fond</h3>
      </div>

      <div class="agent-info">
        <div class="label">Agent connecté</div>
        <div class="value" id="agent-name">Agent #<span id="agent-id">1</span></div>
      </div>

      <form id="fondForm">
        <div class="form-group">
          <label><i class="bi bi-currency-dollar"></i> Montant (Ar)</label>
          <input type="number" id="montant" class="form-control-modern" placeholder="Ex: 1000000" required step="0.01">
        </div>

        <div class="form-group">
          <label><i class="bi bi-tags"></i> Type de fond</label>
          <select id="id_type_fond" class="form-control-modern" required>
            <option value="">-- Sélectionnez un type de fond --</option>
          </select>
        </div>

        <input type="hidden" id="id_agent" value="1">

        <button type="submit" class="btn-primary" id="submitBtn">
          <i class="bi bi-plus-circle"></i> Ajouter le fond
        </button>
      </form>
    </div>
  </div>

  <script>
    const apiBase = "http://localhost/projet_final/ws";
    const agentId = document.getElementById('id_agent').value;

    function showNotification(message, type = 'success') {
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.textContent = message;
      document.body.appendChild(notification);
      
      setTimeout(() => notification.classList.add('show'), 100);
      setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => document.body.removeChild(notification), 300);
      }, 3000);
    }

    function setLoading(isLoading) {
      const submitBtn = document.getElementById('submitBtn');
      if (isLoading) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading">Chargement...</span>';
      } else {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-plus-circle"></i> Ajouter le fond';
      }
    }


//     function chargerAgentDepuisSession() {
//   fetch(apiBase + "/session_user")
//     .then(res => {
//       if (!res.ok) throw new Error("Non connecté");
//       return res.json();
//     })
//     .then(data => {
//       if (data.success && data.id_agent) {
//         document.getElementById("id_agent").value = data.id_agent;
//         console.log("ID agent chargé :", data.id_agent);
//       } else {
//         alert("Session invalide");
//       }
//     })
//     .catch(err => {
//       console.error("Erreur session :", err);
//       alert("Veuillez vous reconnecter.");
//       window.location.href = "/projet_final/views/agents/login.php";
//     });
// }

    function chargerTypesFond() {
      fetch(apiBase + "/typefond")
        .then(res => {
          if (!res.ok) throw new Error('Erreur réseau');
          return res.json();
        })
        .then(data => {
          const select = document.getElementById("id_type_fond");
          select.innerHTML = '<option value="">-- Sélectionnez un type de fond --</option>';
          
          data.forEach(t => {
            const opt = document.createElement("option");
            opt.value = t.id;
            opt.textContent = t.nom;
            select.appendChild(opt);
          });
        })
        .catch(error => {
          console.error('Erreur lors du chargement des types de fond:', error);
          showNotification('Erreur lors du chargement des types de fond', 'error');
        });
    }

    function ajouterFond(event) {
      event.preventDefault();
      
      const montant = document.getElementById("montant").value;
      const id_type_fond = document.getElementById("id_type_fond").value;
      const id_agent = document.getElementById("id_agent").value;

      if (!montant || !id_type_fond || !id_agent) {
        showNotification("Veuillez remplir tous les champs.", 'error');
        return;
      }

      if (parseFloat(montant) <= 0) {
        showNotification("Le montant doit être positif.", 'error');
        return;
      }

      setLoading(true);

      const data = `montant=${encodeURIComponent(montant)}&id_type_fond=${id_type_fond}&id_agent=${id_agent}`;

      fetch(apiBase + "/fond", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: data
      })
      .then(res => {
        if (!res.ok) throw new Error('Erreur réseau');
        return res.json();
      })
      .then(resp => {
        if (resp.success !== false) {
          showNotification(resp.message || "Fond ajouté avec succès !", 'success');
          document.getElementById("fondForm").reset();
        } else {
          showNotification(resp.message || "Erreur lors de l'ajout du fond", 'error');
        }
      })
      .catch(error => {
        console.error('Erreur:', error);
        showNotification("Erreur lors de l'ajout du fond", 'error');
      })
      .finally(() => {
        setLoading(false);
      });
    }

    // Initialisation
    document.getElementById('fondForm').addEventListener('submit', ajouterFond);
    document.getElementById('agent-id').textContent = agentId;

    // Charger les types de fond au démarrage
    window.onload = () => {
      chargerTypesFond();
    };
  </script>
</body>
</html>