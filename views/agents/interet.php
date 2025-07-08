<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Intérêts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

    .mode-toggle {
      display: flex;
      gap: 0.5rem;
      background: var(--hover-bg);
      padding: 0.5rem;
      border-radius: 12px;
      margin-bottom: 2rem;
    }

    .toggle-btn {
      flex: 1;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      border: none;
      background: transparent;
      color: var(--text-secondary);
      font-weight: 600;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .toggle-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: var(--primary-gradient);
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: -1;
    }

    .toggle-btn.active {
      color: var(--text-primary);
      background: var(--primary-gradient);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .toggle-btn:hover:not(.active) {
      color: var(--text-primary);
    }

    .toggle-btn:hover:not(.active)::before {
      opacity: 0.1;
    }

    .filter-form {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 1rem;
      align-items: end;
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

    .btn-search {
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

    .btn-search:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
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

    .amount-simple {
      background: rgba(102, 126, 234, 0.1);
      color: #667eea;
    }

    .amount-compose {
      background: rgba(255, 107, 107, 0.1);
      color: #ff6b6b;
    }

    .chart-container {
      position: relative;
      height: 400px;
      background: var(--card-bg);
      border-radius: 12px;
      padding: 1rem;
    }

    .chart-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .chart-header h3 {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
    }

    .chart-header .icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: var(--primary-gradient);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
    }

    .stats-grid {
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

    .loading-spinner {
      display: none;
      text-align: center;
      padding: 2rem;
    }

    .spinner {
      width: 40px;
      height: 40px;
      border: 3px solid var(--border-color);
      border-top: 3px solid #667eea;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto 1rem;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
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

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }
      
      .main-content {
        margin-left: 0;
        padding: 1rem;
      }
      
      .filter-form {
        grid-template-columns: 1fr;
      }
      
      .mode-toggle {
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
      <a href="#"><i class="bi bi-house-door"></i> Dashboard</a>
      <a href="#"><i class="bi bi-person"></i> Clients</a>
      <a href="#" class="active"><i class="bi bi-bar-chart"></i> Intérêts</a>
      <a href="#"><i class="bi bi-gear"></i> Paramètres</a>
    </nav>
  </div>

  <div class="main-content">
    <div class="page-header">
      <div class="icon">
        <i class="bi bi-graph-up" style="font-size: 1.5rem;"></i>
      </div>
      <div>
        <h1>Gestion des Intérêts</h1>
        <p class="subtitle">Analyse et suivi des intérêts des prêts</p>
      </div>
    </div>

    <div class="stats-grid" id="stats-grid">
      <!-- Stats will be populated by JavaScript -->
    </div>

    <div class="mode-toggle" id="mode-toggle">
      <button type="button" class="toggle-btn active" data-mode="simple">
        <i class="bi bi-calculator"></i> Intérêt Simple
      </button>
      <button type="button" class="toggle-btn" data-mode="compose">
        <i class="bi bi-graph-up"></i> Intérêt Composé
      </button>
      <button type="button" class="toggle-btn" data-mode="all">
        <i class="bi bi-bar-chart-line"></i> Comparaison
      </button>
    </div>

    <div class="card-modern">
      <div class="chart-header">
        <div class="icon">
          <i class="bi bi-funnel"></i>
        </div>
        <h3>Filtres de recherche</h3>
      </div>
      <form id="filtreForm" class="filter-form">
        <div class="form-group">
          <label>Mois début</label>
          <select id="mois_debut" class="form-control-modern">
            <option value="01">Janvier</option>
            <option value="02">Février</option>
            <option value="03">Mars</option>
            <option value="04">Avril</option>
            <option value="05">Mai</option>
            <option value="06">Juin</option>
            <option value="07">Juillet</option>
            <option value="08">Août</option>
            <option value="09">Septembre</option>
            <option value="10">Octobre</option>
            <option value="11">Novembre</option>
            <option value="12">Décembre</option>
          </select>
        </div>
        <div class="form-group">
          <label>Année début</label>
          <input type="number" id="annee_debut" value="2020" class="form-control-modern" />
        </div>
        <div class="form-group">
          <label>Mois fin</label>
          <select id="mois_fin" class="form-control-modern">
            <option value="01">Janvier</option>
            <option value="02">Février</option>
            <option value="03">Mars</option>
            <option value="04">Avril</option>
            <option value="05">Mai</option>
            <option value="06">Juin</option>
            <option value="07">Juillet</option>
            <option value="08">Août</option>
            <option value="09">Septembre</option>
            <option value="10">Octobre</option>
            <option value="11">Novembre</option>
            <option value="12">Décembre</option>
          </select>
        </div>
        <div class="form-group">
          <label>Année fin</label>
          <input type="number" id="annee_fin" value="2050" class="form-control-modern" />
        </div>
        <div class="form-group">
          <button type="submit" class="btn-search">
            <i class="bi bi-search"></i> Rechercher
          </button>
        </div>
      </form>
    </div>

    <div class="card-modern">
      <div class="chart-header">
        <div class="icon">
          <i class="bi bi-table"></i>
        </div>
        <h3>Liste des prêts</h3>
      </div>
      <div class="loading-spinner" id="loading">
        <div class="spinner"></div>
        <p>Chargement des données...</p>
      </div>
      <div class="table-container" id="table-container">
        <table class="table-modern">
          <thead>
            <tr>
              <th>Client</th>
              <th>Montant</th>
              <th>Taux</th>
              <th>Durée</th>
              <th>Date</th>
              <th>Intérêt Total</th>
              <th>Intérêt Mensuel</th>
            </tr>
          </thead>
          <tbody id="table-interets"></tbody>
        </table>
      </div>
      <div class="empty-state" id="empty-state" style="display: none;">
        <i class="bi bi-inbox"></i>
        <h3>Aucune donnée disponible</h3>
        <p>Essayez de modifier vos filtres de recherche</p>
      </div>
    </div>

    <div class="card-modern">
      <div class="chart-header">
        <div class="icon">
          <i class="bi bi-bar-chart-line"></i>
        </div>
        <h3>Évolution des intérêts mensuels</h3>
      </div>
      <div class="chart-container">
        <canvas id="interetChart"></canvas>
      </div>
    </div>
  </div>

  <script>
    const apiBase = "http://localhost/projet_final/ws";

    function showLoading() {
      document.getElementById('loading').style.display = 'block';
      document.getElementById('table-container').style.display = 'none';
      document.getElementById('empty-state').style.display = 'none';
    }

    function hideLoading() {
      document.getElementById('loading').style.display = 'none';
      document.getElementById('table-container').style.display = 'block';
    }

    function updateStats(data) {
      const statsGrid = document.getElementById('stats-grid');
      let totalPrets = data.length;
      let totalMontant = data.reduce((sum, p) => sum + parseFloat(p.montant), 0);
      let totalInteretSimple = data.reduce((sum, p) => sum + parseFloat(p.interet_simple_total), 0);
      let totalInteretCompose = data.reduce((sum, p) => sum + parseFloat(p.interet_compose_total), 0);

      statsGrid.innerHTML = `
        <div class="stat-card">
          <div class="stat-value">${totalPrets.toLocaleString()}</div>
          <div class="stat-label">Prêts actifs</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">${totalMontant.toLocaleString()} Ar</div>
          <div class="stat-label">Montant total</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">${totalInteretSimple.toLocaleString()} Ar</div>
          <div class="stat-label">Intérêt simple total</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">${totalInteretCompose.toLocaleString()} Ar</div>
          <div class="stat-label">Intérêt composé total</div>
        </div>
      `;
    }

    function chargerInterets(mois_debut = null, annee_debut = null, mois_fin = null, annee_fin = null) {
      showLoading();
      
      let url = apiBase + "/interets";
      if (mois_debut && annee_debut && mois_fin && annee_fin) {
        url += `?mois_debut=${mois_debut}&annee_debut=${annee_debut}&mois_fin=${mois_fin}&annee_fin=${annee_fin}`;
      }

      fetch(url)
        .then(res => res.json())
        .then(data => {
          hideLoading();
          
          if (data.length === 0) {
            document.getElementById('empty-state').style.display = 'block';
            document.getElementById('table-container').style.display = 'none';
            return;
          }

          updateStats(data);
          
          const mode = document.querySelector('#mode-toggle .active').dataset.mode;
          const tbody = document.getElementById("table-interets");
          tbody.innerHTML = "";

          let simples = {}, composes = {};

          data.forEach(p => {
            const simpleTotal = parseFloat(p.interet_simple_total).toLocaleString() + " Ar";
            const composeTotal = parseFloat(p.interet_compose_total).toLocaleString() + " Ar";
            const simpleMensuel = parseFloat(p.interet_simple_mensuel).toLocaleString() + " Ar";
            const composeMensuel = parseFloat(p.interet_compose_mensuel).toLocaleString() + " Ar";

            let tr = document.createElement("tr");

            if (mode === "simple") {
              tr.innerHTML = `
                <td><strong>${p.nom_client}</strong></td>
                <td><span class="amount-badge">${parseFloat(p.montant).toLocaleString()} Ar</span></td>
                <td><span style="color: #4facfe;">${p.taux_interet}%</span></td>
                <td>${p.duree_mois} mois</td>
                <td>${p.mois}</td>
                <td><span class="amount-badge amount-simple">${simpleTotal}</span></td>
                <td><span class="amount-badge amount-simple">${simpleMensuel}</span></td>`;
            } else if (mode === "compose") {
              tr.innerHTML = `
                <td><strong>${p.nom_client}</strong></td>
                <td><span class="amount-badge">${parseFloat(p.montant).toLocaleString()} Ar</span></td>
                <td><span style="color: #4facfe;">${p.taux_interet}%</span></td>
                <td>${p.duree_mois} mois</td>
                <td>${p.mois}</td>
                <td><span class="amount-badge amount-compose">${composeTotal}</span></td>
                <td><span class="amount-badge amount-compose">${composeMensuel}</span></td>`;
            } else {
              tr.innerHTML = `
                <td><strong>${p.nom_client}</strong></td>
                <td><span class="amount-badge">${parseFloat(p.montant).toLocaleString()} Ar</span></td>
                <td><span style="color: #4facfe;">${p.taux_interet}%</span></td>
                <td>${p.duree_mois} mois</td>
                <td>${p.mois}</td>
                <td>
                  <div>
                    <span class="amount-badge amount-simple" style="display: block; margin-bottom: 0.25rem;">Simple: ${simpleTotal}</span>
                    <span class="amount-badge amount-compose">Composé: ${composeTotal}</span>
                  </div>
                </td>
                <td>
                  <div>
                    <span class="amount-badge amount-simple" style="display: block; margin-bottom: 0.25rem;">Simple: ${simpleMensuel}</span>
                    <span class="amount-badge amount-compose">Composé: ${composeMensuel}</span>
                  </div>
                </td>`;
            }

            tbody.appendChild(tr);

            const mois = p.mois;
            simples[mois] = (simples[mois] || 0) + parseFloat(p.interet_simple_mensuel);
            composes[mois] = (composes[mois] || 0) + parseFloat(p.interet_compose_mensuel);
          });

          // Chart
          const labels = [...new Set([...Object.keys(simples), ...Object.keys(composes)])].sort();
          const ctx = document.getElementById("interetChart").getContext("2d");
          if (window.myChart) window.myChart.destroy();

          window.myChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: labels,
              datasets: [
                ...(mode === 'simple' || mode === 'all' ? [{
                  label: "Intérêt simple",
                  data: labels.map(m => simples[m] || 0),
                  borderColor: "#667eea",
                  backgroundColor: "rgba(102, 126, 234, 0.1)",
                  tension: 0.4,
                  fill: true,
                  pointBackgroundColor: "#667eea",
                  pointBorderColor: "#ffffff",
                  pointBorderWidth: 2,
                  pointRadius: 5
                }] : []),
                ...(mode === 'compose' || mode === 'all' ? [{
                  label: "Intérêt composé",
                  data: labels.map(m => composes[m] || 0),
                  borderColor: "#ff6b6b",
                  backgroundColor: "rgba(255, 107, 107, 0.1)",
                  tension: 0.4,
                  fill: true,
                  pointBackgroundColor: "#ff6b6b",
                  pointBorderColor: "#ffffff",
                  pointBorderWidth: 2,
                  pointRadius: 5
                }] : [])
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  labels: {
                    color: '#8b949e',
                    font: {
                      size: 12,
                      weight: 500
                    }
                  }
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    color: '#8b949e'
                  },
                  grid: {
                    color: '#30363d'
                  }
                },
                x: {
                  ticks: {
                    autoSkip: true,
                    maxTicksLimit: 12,
                    color: '#8b949e'
                  },
                  grid: {
                    color: '#30363d'
                  }
                }
              }
            }
          });
        })
        .catch(error => {
          hideLoading();
          console.error('Erreur:', error);
          document.getElementById('empty-state').style.display = 'block';
          document.getElementById('table-container').style.display = 'none';
        });
    }

    document.getElementById("filtreForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const m1 = document.getElementById("mois_debut").value;
      const y1 = document.getElementById("annee_debut").value;
      const m2 = document.getElementById("mois_fin").value;
      const y2 = document.getElementById("annee_fin").value;
      chargerInterets(m1, y1, m2, y2);
    });

    document.querySelectorAll('#mode-toggle .toggle-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('#mode-toggle .toggle-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById("filtreForm").dispatchEvent(new Event("submit"));
      });
    });

    window.onload = () => chargerInterets();
  </script>
</body>
</html>