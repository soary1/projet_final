<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Suivi des Intérêts - Banque</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    .form-filters {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      align-items: end;
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

    .chart-container {
      position: relative;
      height: 400px;
      margin-bottom: 2rem;
    }

    .table-container {
      overflow-x: auto;
      border-radius: 12px;
      background: var(--card-bg);
      border: 1px solid var(--border-color);
    }

    .table-modern {
      width: 100%;
      background: transparent;
      color: var(--text-primary);
      margin: 0;
    }

    .table-modern thead {
      background: var(--hover-bg);
      border-bottom: 2px solid var(--border-color);
    }

    .table-modern th {
      padding: 1rem 1.5rem;
      font-weight: 600;
      color: var(--text-secondary);
      text-transform: uppercase;
      font-size: 0.8rem;
      letter-spacing: 0.5px;
      border: none;
    }

    .table-modern td {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid var(--border-color);
      vertical-align: middle;
      border-left: none;
      border-right: none;
    }

    .table-modern tbody tr {
      transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
      background: var(--hover-bg);
    }

    .table-modern tbody tr:last-child td {
      border-bottom: none;
    }

    .amount-badge {
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }

    .amount-prevu {
      background: rgba(79, 172, 254, 0.1);
      color: #4facfe;
    }

    .amount-reel {
      background: rgba(39, 174, 96, 0.1);
      color: #27ae60;
    }

    .stats-badge {
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      background: rgba(102, 126, 234, 0.1);
      color: #667eea;
    }

    .retard-badge {
      background: rgba(255, 107, 107, 0.1);
      color: #ff6b6b;
    }

    .empty-state {
      text-align: center;
      padding: 3rem;
      color: var(--text-secondary);
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    .hidden {
      display: none;
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

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }
      
      .main-content {
        margin-left: 0;
        padding: 1rem;
      }
      
      .form-filters {
        grid-template-columns: 1fr;
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
      <a href="pres/liste_prets.html"><i class="bi bi-card-list"></i> Voir les prêts</a>
      <a href="pres/prets-attente.html"><i class="bi bi-clock-history"></i> Prêts en attente</a>
      <a href="interet.php" class="active"><i class="bi bi-bar-chart"></i> Intérêts</a>
      <a href="simulation.php"><i class="bi bi-calculator"></i> Simulation Prêt</a>
    </nav>
  </div>

  <div class="main-content">
    <div class="page-header">
      <div class="icon">
        <i class="bi bi-bar-chart" style="font-size: 1.5rem;"></i>
      </div>
      <div>
        <h1>Suivi des Intérêts</h1>
        <p class="subtitle">Analyse des intérêts prévus vs réels par période</p>
      </div>
    </div>

    <div class="card-modern">
      <div class="card-header">
        <div class="icon">
          <i class="bi bi-funnel"></i>
        </div>
        <h3>Filtres de Période</h3>
      </div>
      <form id="filtreForm">
        <div class="form-filters">
          <div class="form-group">
            <label><i class="bi bi-calendar-date"></i> Mois de début</label>
            <select id="moisDebut" class="form-control-modern" required>
              <option value="">-- Mois --</option>
            </select>
          </div>
          <div class="form-group">
            <label><i class="bi bi-calendar3"></i> Année de début</label>
            <select id="anneeDebut" class="form-control-modern" required>
              <option value="">-- Année --</option>
            </select>
          </div>
          <div class="form-group">
            <label><i class="bi bi-calendar-date"></i> Mois de fin</label>
            <select id="moisFin" class="form-control-modern" required>
              <option value="">-- Mois --</option>
            </select>
          </div>
          <div class="form-group">
            <label><i class="bi bi-calendar3"></i> Année de fin</label>
            <select id="anneeFin" class="form-control-modern" required>
              <option value="">-- Année --</option>
            </select>
          </div>
          <div class="form-group">
            <button type="submit" class="btn-primary" id="submitBtn">
              <i class="bi bi-search"></i> Analyser
            </button>
          </div>
        </div>
      </form>
    </div>

    <div class="card-modern hidden" id="graphiqueCard">
      <div class="card-header">
        <div class="icon">
          <i class="bi bi-graph-up"></i>
        </div>
        <h3>Graphique Comparatif</h3>
      </div>
      <div class="chart-container">
        <canvas id="graphique"></canvas>
      </div>
    </div>

    <div class="card-modern hidden" id="tableauCard">
      <div class="card-header">
        <div class="icon">
          <i class="bi bi-table"></i>
        </div>
        <h3>Détails par Mois</h3>
      </div>
      <div class="table-container">
        <table class="table-modern">
          <thead>
            <tr>
              <th>Mois</th>
              <th>Mensualité (VPM)</th>
              <th>Intérêt Prévu</th>
              <th>Intérêt Réel</th>
              <th>Remboursements</th>
              <th>Retards</th>
            </tr>
          </thead>
          <tbody id="resultatTableBody"></tbody>
        </table>
      </div>
      <div class="empty-state" id="empty-state">
        <i class="bi bi-bar-chart"></i>
        <h3>Aucune donnée</h3>
        <p>Sélectionnez une période pour voir les résultats</p>
      </div>
    </div>
  </div>

  <script>
    const moisNoms = [
      "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
      "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
    ];
    const moisOptions = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];

    let chart = null;

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
        submitBtn.innerHTML = '<i class="bi bi-search"></i> Analyser';
      }
    }

    function remplirSelects() {
      const now = new Date();
      const annee = now.getFullYear();
      const mois = now.getMonth() + 1;
      
      // Remplir les mois
      for (let i = 0; i < moisOptions.length; i++) {
        const option1 = document.createElement('option');
        option1.value = moisOptions[i];
        option1.textContent = moisNoms[i];
        document.getElementById("moisDebut").appendChild(option1);
        
        const option2 = document.createElement('option');
        option2.value = moisOptions[i];
        option2.textContent = moisNoms[i];
        document.getElementById("moisFin").appendChild(option2);
      }
      
      // Remplir les années
      for (let y = annee - 5; y <= annee + 1; y++) {
        const option1 = document.createElement('option');
        option1.value = y;
        option1.textContent = y;
        document.getElementById("anneeDebut").appendChild(option1);
        
        const option2 = document.createElement('option');
        option2.value = y;
        option2.textContent = y;
        document.getElementById("anneeFin").appendChild(option2);
      }
      
      // Définir des valeurs par défaut
      document.getElementById("moisDebut").value = "01";
      document.getElementById("anneeDebut").value = annee;
      document.getElementById("moisFin").value = String(mois).padStart(2, '0');
      document.getElementById("anneeFin").value = annee;
    }

    document.getElementById("filtreForm").addEventListener("submit", function(e) {
      e.preventDefault();

      const mois_debut = document.getElementById("moisDebut").value;
      const annee_debut = document.getElementById("anneeDebut").value;
      const mois_fin = document.getElementById("moisFin").value;
      const annee_fin = document.getElementById("anneeFin").value;

      if (!mois_debut || !annee_debut || !mois_fin || !annee_fin) {
        showNotification("Veuillez sélectionner toutes les dates", 'error');
        return;
      }

      setLoading(true);

      fetch(`http://localhost/projet_final/ws/interets-par-mois?mois_debut=${mois_debut}&annee_debut=${annee_debut}&mois_fin=${mois_fin}&annee_fin=${annee_fin}`)
        .then(res => {
          if (!res.ok) throw new Error('Erreur réseau');
          return res.json();
        })
        .then(data => {
          if (data.success) {
            afficherGraphique(data.data);
            showNotification("Données chargées avec succès", 'success');
          } else {
            showNotification(data.message || "Erreur serveur", 'error');
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          showNotification("Erreur lors du chargement des données", 'error');
        })
        .finally(() => {
          setLoading(false);
        });
    });

    function afficherGraphique(donnees) {
        const tbody = document.getElementById("resultatTableBody");
        const graphiqueCard = document.getElementById("graphiqueCard");
        const tableauCard = document.getElementById("tableauCard");
        const emptyState = document.getElementById("empty-state");

        tbody.innerHTML = "";

        if (donnees.length === 0) {
            emptyState.style.display = "block";
            graphiqueCard.classList.add("hidden");
            tableauCard.classList.add("hidden");
            return;
        }

        emptyState.style.display = "none";
        graphiqueCard.classList.remove("hidden");
        tableauCard.classList.remove("hidden");

        const labels = [], mensualites = [], interetsPrevus = [], interetsReels = [];

        donnees.forEach(ligne => {
            labels.push(ligne.mois);
            mensualites.push(parseFloat(ligne.mensualite_vpm));
            interetsPrevus.push(parseFloat(ligne.interet_prevu));
            interetsReels.push(parseFloat(ligne.interet_reel));

            const tr = document.createElement("tr");
            tr.innerHTML = `
            <td><strong>${ligne.mois}</strong></td>
            <td><span class="amount-badge">${parseFloat(ligne.mensualite_vpm).toLocaleString()} Ar</span></td>
            <td><span class="amount-badge amount-prevu">${parseFloat(ligne.interet_prevu).toLocaleString()} Ar</span></td>
            <td><span class="amount-badge amount-reel">${parseFloat(ligne.interet_reel).toLocaleString()} Ar</span></td>
            <td><span class="stats-badge">${ligne.nb_remboursements}</span></td>
            <td><span class="stats-badge ${ligne.nb_retards > 0 ? 'retard-badge' : ''}">${ligne.nb_retards}</span></td>
            `;

            tbody.appendChild(tr);
        });

        const ctx = document.getElementById("graphique").getContext("2d");
        if (chart) chart.destroy();

        chart = new Chart(ctx, {
            type: "bar",
            data: {
            labels: labels,
            datasets: [
                {
                label: "Mensualité (VPM)",
                data: mensualites,
                backgroundColor: "rgba(102, 126, 234, 0.7)",
                borderColor: "#667eea",
                borderWidth: 1
                },
                {
                label: "Intérêt Prévu",
                data: interetsPrevus,
                backgroundColor: "rgba(79, 172, 254, 0.8)",
                borderColor: "#4facfe",
                borderWidth: 1
                },
                {
                label: "Intérêt Réel",
                data: interetsReels,
                backgroundColor: "rgba(39, 174, 96, 0.8)",
                borderColor: "#27ae60",
                borderWidth: 1
                }
            ]
            },
            options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                labels: {
                    color: '#ffffff'
                }
                }
            },
            scales: {
                x: {
                ticks: {
                    color: '#8b949e'
                },
                grid: {
                    color: '#30363d'
                }
                },
                y: {
                beginAtZero: true,
                ticks: {
                    color: '#8b949e',
                    callback: function(value) {
                    return value.toLocaleString() + ' Ar';
                    }
                },
                grid: {
                    color: '#30363d'
                }
                }
            }
            }
        });
        }

    window.onload = () => {
      remplirSelects();
    };
  </script>
</body>
</html>