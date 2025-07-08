<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Simulation de prêt</title>
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

    .compare-row.highlight-winner {
  background-color: rgba(255, 255, 255, 0.05);
  border-radius: 6px;
  padding: 4px 8px;
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

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

    .btn-success {
      background: var(--success-gradient);
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

    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(79, 172, 254, 0.3);
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

    .amount-principal {
      background: rgba(79, 172, 254, 0.1);
      color: #4facfe;
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
      
      .stats-grid {
        grid-template-columns: 1fr;
      }
    }
    .comparison-grid {
  display: flex;
  gap: 1.5rem;
  justify-content: space-around;
  flex-wrap: wrap;
  margin-top: 1rem;
}

.compare-card {
  flex: 1;
  min-width: 300px;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  background: var(--card-bg);
  padding: 1.5rem;
  box-shadow: 0 4px 16px rgba(0,0,0,0.2);
}

.compare-card h4 {
  margin-bottom: 1rem;
  font-size: 1.2rem;
  color: var(--text-primary);
  text-align: center;
  background: var(--primary-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.compare-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
  color: var(--text-secondary);
}
.compare-row span {
  font-weight: 600;
  color: var(--text-primary);
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
      <a href="pres/liste_prets.html"><i class="bi bi-card-list"></i> Remboursement</a>
      <a href="pres/liste_prets.html"><i class="bi bi-card-list"></i> Export Pdf</a>
      <a href="pres/prets-attente.html"><i class="bi bi-clock-history"></i> Prêts en attente</a>
      <a href="interet.php" class="bi bi-calculator"><i class="bi bi-bar-chart"></i> Intérêts</a>
      <a href="interets_par_mois.php" class="bi bi-calculator"><i class="bi bi-bar-chart"></i> Intérêts Gagné Par mois</a>
      <a href="#" class="active"><i class="bi bi-calculator"></i> Simulation</a>
    </nav>
  </div>

  <div class="main-content">
    <div class="page-header">
      <div class="icon">
        <i class="bi bi-calculator" style="font-size: 1.5rem;"></i>
      </div>
      <div>
        <h1>Simulation de Prêts</h1>
        <p class="subtitle">Calculez et comparez les différents types d'intérêts</p>
      </div>
    </div>

    <div class="stats-grid" id="stats-grid">
      <div class="stat-card">
        <div class="stat-value" id="stat-simulations">0</div>
        <div class="stat-label">Simulations</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="stat-montant">0 Ar</div>
        <div class="stat-label">Montant total</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="stat-interet-simple">0 Ar</div>
        <div class="stat-label">Intérêt simple total</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="stat-interet-compose">0 Ar</div>
        <div class="stat-label">Intérêt composé total</div>
      </div>
    </div>

    <div class="card-modern">
      <div class="card-header">
        <div class="icon">
          <i class="bi bi-calculator"></i>
        </div>
        <h3>Nouvelle Simulation</h3>
      </div>
      <form id="simulationForm">
        <div class="form-grid">
          <div class="form-group">
  <label><i class="bi bi-tags"></i> Type de prêt</label>
  <select id="typePretSelect" class="form-control-modern">
    <option value="">Choisir un type existant (optionnel)</option>
  </select>
</div>

          <div class="form-group">
            <label><i class="bi bi-currency-dollar"></i> Montant (Ar)</label>
            <input type="number" step="1000" id="montant" class="form-control-modern" placeholder="Ex: 1000000" required>
          </div>
          <div class="form-group">
            <label><i class="bi bi-percent"></i> Taux Annuel (%)</label>
            <input type="number" step="0.01" id="taux" class="form-control-modern" placeholder="Ex: 3.5" required>
          </div>
          <div class="form-group">
            <label><i class="bi bi-calendar"></i> Durée (mois)</label>
            <input type="number" id="duree" class="form-control-modern" placeholder="Ex: 240" required>
          </div>
          <div class="form-group">
  <label><i class="bi bi-shield-check"></i> Assurance (%)</label>
  <input type="number" step="0.01" id="assurance" class="form-control-modern" placeholder="Ex: 0.5">
</div>

<div class="form-group">
  <label><i class="bi bi-hourglass-split"></i> Délai de Défaut (mois)</label>
  <input type="number" id="delai" class="form-control-modern" placeholder="Ex: 1">
</div>

          <div class="form-group">
            <label style="opacity: 0;">Action</label>
            <button type="submit" class="btn-primary">
              <i class="bi bi-plus-circle"></i> Ajouter à la simulation
            </button>
          </div>
        </div>
      </form>
    </div>

    <div class="card-modern hidden" id="validationCard">
      <div class="card-header">
        <div class="icon">
          <i class="bi bi-check-circle"></i>
        </div>
        <h3>Validation des Simulations</h3>
      </div>
      <form id="validationForm">
        <div class="form-grid">
          <div class="form-group">
            <label><i class="bi bi-person"></i> Client</label>
            <select id="clientSelect" name="id_client" class="form-control-modern" required>
              <option value="">Sélectionnez un client</option>
            </select>
          </div>
          <div class="form-group">
            <label><i class="bi bi-person-badge"></i> Agent</label>
            <select id="agentSelect" name="id_agent" class="form-control-modern" required>
              <option value="">Sélectionnez un agent</option>
            </select>
          </div>
          <div class="form-group">
            <label style="opacity: 0;">Action</label>
            <button type="submit" class="btn-success">
              <i class="bi bi-check-circle"></i> Enregistrer simulation
            </button>
          </div>
        </div>
      </form>
    </div>
    <div id="comparaisonContainer" class="card-modern hidden">
  <div class="card-header">
    <div class="icon"><i class="bi bi-columns-gap"></i></div>
    <h3>Comparaison des Simulations</h3>
  </div>
  <div id="comparaisonResult" class="comparison-grid">
    <!-- les fiches seront injectées ici -->
  </div>
</div>


    <div class="card-modern hidden" id="resultatCard">
      <div class="card-header">
        <div class="icon">
          <i class="bi bi-table"></i>
        </div>
        <h3>Résultats des Simulations</h3>
      </div>
      <div class="table-container">
        <table class="table-modern">
          <thead>
            <tr>
              <th>#</th>
              <th>Montant</th>
              <th>Taux</th>
              <th>Durée</th>
              <th>Intérêt Simple Total</th>
              <th>Mensuel Simple</th>
              <th>Intérêt Composé Total</th>
              <th>Mensuel Composé</th>
<th>Assurance Totale</th>
<th>Assurance Mensuelle</th>
<th>Délai défaut</th>
<th>Mensualité VPN</th>

              <th>Action</th>


            </tr>
          </thead>
          <tbody id="resultats"></tbody>
        </table>
      </div>
      <div class="empty-state" id="empty-state">
        <i class="bi bi-calculator"></i>
        <h3>Aucune simulation</h3>
        <p>Ajoutez une simulation pour voir les résultats</p>
      </div>
    </div>
  </div>

<div style="display: flex; justify-content: center;">
  <div class="card-modern" id="listeSimulations">
      <div class="card-header">
          <div class="icon">
              <i class="bi bi-clock-history"></i>
          </div>
          <h3>Historique des simulations enregistrées</h3>
      </div>
      <div class="table-container">
          <table class="table-modern">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Client</th>
                      <th>Montant</th>
                      <th>Taux</th>
                      <th>Durée</th>
                      <th>Mensualité</th>
                      <th>Assurance</th>
                      <th>Délai défaut</th>
                      <th>Sélectionner</th>
                  </tr>
              </thead>
              <tbody id="historique-simulations">
                  <tr><td colspan="10">Chargement...</td></tr>
              </tbody>
          </table>

          <button onclick="compareSimulations()" class="btn-primary" style="margin-top: 20px;">
              Comparer les simulations sélectionnées
          </button>
      </div>
  </div>
</div>





  <script>
    const simulations = [];

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
    function chargerListeSimulations() {
  fetch("http://localhost/projet_final/ws/simulations")
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById("historique-simulations");
      tbody.innerHTML = "";

      if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="10" class="empty-state">Aucune simulation enregistrée</td></tr>`;
        return;
      }

      data.forEach((s, index) => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
        <td>${index + 1}</td>
        <td>${s.id_client || "—"}</td>
        <td>${parseFloat(s.montant).toLocaleString()} Ar</td>
        <td>${s.taux_annuel}%</td>
        <td>${s.duree_mois} mois</td>
        <td>${parseFloat(s.mensualite).toLocaleString()} Ar</td>
        <td>${parseFloat(s.assurance || 0).toLocaleString()}%</td>
        <td>${s.delai || 0} mois</td>
        <td>
            <input type="checkbox" class="compare-checkbox" data-id="${s.id}">
        </td>
    `;
    tbody.appendChild(tr);
});

    })
    .catch(() => {
      document.getElementById("historique-simulations").innerHTML = `
        <tr><td colspan="10" class="text-danger">Erreur lors du chargement des données</td></tr>
      `;
    });
}
function compareSimulations() {
    // Récupérer les simulations sélectionnées
    const selectedSimulations = document.querySelectorAll('.compare-checkbox:checked');
    
    if (selectedSimulations.length !== 2) {
        alert('Veuillez sélectionner exactement deux simulations pour la comparaison.');
        return;
    }

    // Récupérer les IDs des simulations sélectionnées
    const simulationIds = Array.from(selectedSimulations).map(checkbox => checkbox.dataset.id);
    
    // Effectuer une requête AJAX vers la route /comparaison avec les IDs des simulations
fetch("http://localhost/projet_final/ws/comparaison?ids=" + simulationIds.join(','))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Traitement des données de comparaison
                afficherComparaison(data.simulations);
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données:', error);
            alert('Une erreur s\'est produite lors de la comparaison des simulations.');
        });
}

function afficherComparaison(simulations) {
  const container = document.getElementById("comparaisonContainer");
  const grid = document.getElementById("comparaisonResult");

  container.classList.remove("hidden");
  grid.innerHTML = "";

  const champs = [
    { label: "Montant", key: "montant", lowerIsBetter: true },
    { label: "Taux annuel", key: "taux_annuel", lowerIsBetter: true },
    { label: "Durée", key: "duree_mois", lowerIsBetter: true },
    { label: "Mensualite", key: "mensualite", lowerIsBetter: true },
    { label: "Assurance", key: "assurance", lowerIsBetter: true },
    // { label: "Délai de défaut", key: "delai_defaut", lowerIsBetter: false }
  ];

  const cartes = [[], []]; // stocke les lignes HTML de chaque carte

  champs.forEach(champ => {
    const [val1, val2] = simulations.map(s => parseFloat(s[champ.key] ?? 0));
    let gagnant = null;

    if (!isNaN(val1) && !isNaN(val2) && val1 !== val2) {
      gagnant = champ.lowerIsBetter ? (val1 < val2 ? 0 : 1) : (val1 > val2 ? 0 : 1);
    }

    simulations.forEach((s, i) => {
      const valeur = s[champ.key] ?? "—";
      const formatée = typeof valeur === "number" || !isNaN(parseFloat(valeur))
        ? parseFloat(valeur).toLocaleString()
        : valeur;

      cartes[i].push(`
        <div class="compare-row ${i === gagnant ? "highlight-winner" : ""}">
          <div>${champ.label}</div>
          <span>${formatée}${champ.label.includes("Montant") || champ.label.includes("Mensualite") ? " Ar" : champ.label.includes("Taux") || champ.label.includes("Assurance") ? "%" : champ.label.includes("Durée") || champ.label.includes("défaut") ? " mois" : ""}</span>
        </div>
      `);
    });
  });

  simulations.forEach((s, i) => {
    const card = document.createElement("div");
    card.className = "compare-card";
    card.innerHTML = `
      <h4>Simulation ${i + 1}</h4>
      <div class="compare-row"><div>Client</div><span>${s.id_client}</span></div>
      ${cartes[i].join("")}
    `;
    grid.appendChild(card);
  });
}






    function updateStats() {
      const totalSimulations = simulations.length;
      const totalMontant = simulations.reduce((sum, s) => sum + s.montant, 0);
      const totalInteretSimple = simulations.reduce((sum, s) => {
        const interet = s.montant * (s.taux / 100) * (s.duree / 12);
        return sum + interet;
      }, 0);
      const totalInteretCompose = simulations.reduce((sum, s) => {
        const taux_mensuel = s.taux / 100 / 12;
        if (taux_mensuel > 0 && s.duree > 0) {
          const mensualite = s.montant * taux_mensuel / (1 - Math.pow(1 + taux_mensuel, -s.duree));
          const total_rembourse = mensualite * s.duree;
          const interet = total_rembourse - s.montant;
          return sum + interet;
        }
        return sum;
      }, 0);

      document.getElementById('stat-simulations').textContent = totalSimulations;
      document.getElementById('stat-montant').textContent = totalMontant.toLocaleString() + ' Ar';
      document.getElementById('stat-interet-simple').textContent = totalInteretSimple.toLocaleString() + ' Ar';
      document.getElementById('stat-interet-compose').textContent = totalInteretCompose.toLocaleString() + ' Ar';
    }

    function chargerClientsEtAgents() {
      fetch("http://localhost/projet_final/ws/clients-agents")
        .then(res => res.json())
        .then(data => {
          const clientSelect = document.getElementById("clientSelect");
          const agentSelect = document.getElementById("agentSelect");

          clientSelect.innerHTML = '<option value="">Sélectionnez un client</option>';
          agentSelect.innerHTML = '<option value="">Sélectionnez un agent</option>';

          data.clients.forEach(c => {
            const opt = document.createElement("option");
            opt.value = c.id;
            opt.textContent = c.nom;
            clientSelect.appendChild(opt);
          });

          data.agents.forEach(a => {
            const opt = document.createElement("option");
            opt.value = a.id;
            opt.textContent = a.nom;
            agentSelect.appendChild(opt);
          });
        })
        .catch(error => {
          console.error('Erreur lors du chargement:', error);
          showNotification('Erreur lors du chargement des clients et agents', 'error');
        });
    }
    fetch("http://localhost/projet_final/ws/types-pret")
  .then(res => res.json())
  .then(data => {
    const select = document.getElementById("typePretSelect");
    data.forEach(t => {
      const opt = document.createElement("option");
      opt.value = JSON.stringify(t);
      opt.textContent = `${t.nom} (${t.taux_interet}% / ${t.duree_mois} mois / ${t.assurance}% / ${t.delai_defaut}j)`;
      select.appendChild(opt);
    });
  });


    function supprimerSimulation(index) {
      simulations.splice(index, 1);
      afficherResultats();
      updateStats();
      showNotification('Simulation supprimée');
    }

    document.getElementById("simulationForm").addEventListener("submit", function(e) {
      e.preventDefault();

      const montant = parseFloat(document.getElementById("montant").value);
      const taux = parseFloat(document.getElementById("taux").value);
      const duree = parseInt(document.getElementById("duree").value);
      const assurance = parseFloat(document.getElementById("assurance").value || 0);
const delai = parseInt(document.getElementById("delai").value || 0);

      if (montant <= 0 || taux <= 0 || duree <= 0) {
        showNotification('Veuillez saisir des valeurs positives', 'error');
        return;
      }

simulations.push({ montant, taux, duree, assurance, delai });      afficherResultats();
      updateStats();
      
      // Reset form
      document.getElementById("simulationForm").reset();
      showNotification('Simulation ajoutée avec succès veuillez actualiser la page pour voir les résultats', 'success');
    });

    function afficherResultats() {
      const tbody = document.getElementById("resultats");
      const resultatCard = document.getElementById("resultatCard");
      const validationCard = document.getElementById("validationCard");
      const emptyState = document.getElementById("empty-state");

      if (simulations.length === 0) {
        resultatCard.classList.add("hidden");
        validationCard.classList.add("hidden");
        return;
      }

      resultatCard.classList.remove("hidden");
      validationCard.classList.remove("hidden");
      emptyState.style.display = "none";

      tbody.innerHTML = "";

      simulations.forEach((s, index) => {
        const taux_mensuel = s.taux / 100 / 12;
        const interet_simple_total = s.montant * (s.taux / 100) * (s.duree / 12);
        const interet_simple_mensuel = interet_simple_total / s.duree;

        let interet_compose_total = 0;
        let interet_compose_mensuel = 0;
        if (taux_mensuel > 0 && s.duree > 0) {
          const mensualite = s.montant * taux_mensuel / (1 - Math.pow(1 + taux_mensuel, -s.duree));
          const total_rembourse = mensualite * s.duree;
          interet_compose_total = total_rembourse - s.montant;
          interet_compose_mensuel = interet_compose_total / s.duree;
        }
        const assurance_total = s.montant * (s.assurance / 100);
const assurance_mensuelle = assurance_total / s.duree;
const mensualite_composee = interet_compose_mensuel + (s.montant / s.duree); // mensualité calculée déjà
const mensualite_vpn = mensualite_composee + assurance_mensuelle;
// const mensualite_vpn = (s.montant * (s.taux / 100 / 12)) / (1 - Math.pow(1 + (s.taux / 100 / 12), -s.duree)) + assurance_mensuelle;



        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td><strong>${index + 1}</strong></td>
          <td><span class="amount-badge amount-principal">${s.montant.toLocaleString()} Ar</span></td>
          <td><span style="color: #4facfe;">${s.taux}%</span></td>
          <td>${s.duree} mois</td>
          <td><span class="amount-badge amount-simple">${interet_simple_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')} Ar</span></td>
          <td><span class="amount-badge amount-simple">${interet_simple_mensuel.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')} Ar</span></td>
          <td><span class="amount-badge amount-compose">${interet_compose_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')} Ar</span></td>
          <td><span class="amount-badge amount-compose">${interet_compose_mensuel.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')} Ar</span></td>
          <td><span class="amount-badge amount-principal">${assurance_total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')} Ar</span></td>
<td><span class="amount-badge amount-principal">${assurance_mensuelle.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')} Ar</span></td>

<td>${s.delai} mois</td>
<td><span class="amount-badge amount-principal">${mensualite_vpn.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')} Ar</span></td>


          <td>
            <button onclick="supprimerSimulation(${index})" style="background: var(--secondary-gradient); border: none; color: white; padding: 0.5rem; border-radius: 6px; cursor: pointer;">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    document.getElementById("validationForm").addEventListener("submit", function(e) {
      e.preventDefault();

      const id_client = document.getElementById("clientSelect").value;
      const id_agent = document.getElementById("agentSelect").value;

      if (!id_client || !id_agent) {
        showNotification('Veuillez sélectionner un client et un agent', 'error');
        return;
      }

      if (simulations.length === 0) {
        showNotification('Aucune simulation à enregistrer', 'error');
        return;
      }

      fetch("http://localhost/projet_final/ws/simulation/valider", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          prets: simulations,
          id_client,
          id_agent
        })
      })
      .then(res => res.json())
      .then(res => {
        if (!res.success && res.message === 'type_pret manquant') {
          if (confirm("Aucun type de prêt similaire trouvé. Voulez-vous l'ajouter dans la base ?")) {
            // Ajouter tous les types manquants
            const promises = simulations.map(p => 
              fetch("http://localhost/projet_final/ws/typepret", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                  nom: `Auto-${p.taux}%/${p.duree}m`,
                  taux_interet: p.taux,
                  duree_mois: p.duree
                })
              })
            );

            Promise.all(promises)
              .then(() => {
                showNotification("Types de prêt ajoutés. Cliquez à nouveau sur 'Enregistrer' pour réessayer.", 'success');
              })
              .catch(() => {
                showNotification("Erreur lors de l'ajout des types de prêt", 'error');
              });
          }
        } else if (res.success) {
          showNotification("Simulations enregistrées avec succès !", 'success');
          simulations.length = 0;
          afficherResultats();
          updateStats();
          document.getElementById("validationForm").reset();
        } else {
          showNotification(res.message || "Erreur lors de l'enregistrement", 'error');
        }
      })
      .catch(err => {
        console.error('Erreur:', err);
        showNotification("Erreur lors de l'enregistrement", 'error');
      });
    });

    window.onload = () => {
  chargerClientsEtAgents();
  chargerListeSimulations();

  updateStats();
  

  // Charger les types de prêt + écouter les changements
  fetch("http://localhost/projet_final/ws/types-pret")
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById("typePretSelect");
      data.forEach(t => {
        const opt = document.createElement("option");
        opt.value = JSON.stringify(t);
        opt.textContent = `${t.nom} (${t.taux_interet}% / ${t.duree_mois} mois / ${t.assurance}% / ${t.delai_defaut}j)`;
        select.appendChild(opt);
      });

      // Ajoute l'écouteur ici (après avoir rempli le select)
      select.addEventListener("change", function () {
        const value = this.value;
        if (!value) return;
        const type = JSON.parse(value);
        document.getElementById("taux").value = type.taux_interet;
        document.getElementById("duree").value = type.duree_mois;
        document.getElementById("assurance").value = type.assurance;
        document.getElementById("delai").value = type.delai_defaut;
      });
    });
};

  </script>
</body>
</html>