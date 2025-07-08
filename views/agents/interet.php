<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Intérêts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fc;
    }
    .sidebar {
      height: 100vh;
      background-color: #1f2d56;
      padding-top: 30px;
      position: fixed;
      width: 220px;
      color: #fff;
    }
    .sidebar h2 {
      font-size: 24px;
      text-align: center;
      margin-bottom: 30px;
      color: #5c6ef8;
    }
    .sidebar a {
      display: block;
      color: #dfe3ee;
      text-decoration: none;
      padding: 12px 20px;
      transition: background 0.2s;
    }
    .sidebar a:hover {
      background-color: #33407a;
    }
    .main-content {
      margin-left: 220px;
      padding: 30px;
    }
    .card-custom {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      background-color: #fff;
      padding: 20px;
      margin-bottom: 30px;
    }
    canvas {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      max-height: 400px;
      width: 100% !important;
    }
    .toggle-btn {
      padding: 8px 20px;
      border-radius: 20px;
      border: none;
      background-color: transparent;
      color: #333;
      font-weight: 600;
      transition: all 0.2s ease;
      border: 1px solid transparent;
    }
    .toggle-btn.active {
      background-color: #1f2d56;
      color: white;
      border-color: #1f2d56;
    }
    select, input[type="number"], button[type="submit"] {
      margin: 5px;
      padding: 6px 12px;
      border-radius: 5px;
      border: 1px solid #ced4da;
    }
    button[type="submit"] {
      background-color: #5c6ef8;
      color: white;
      border: none;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>EF Mada</h2>
    <a href="#"><i class="bi bi-house-door"></i> Dashboard</a>
    <a href="#"><i class="bi bi-person"></i> Clients</a>
    <a href="#"><i class="bi bi-bar-chart"></i> Intérêts</a>
    <a href="#"><i class="bi bi-gear"></i> Paramètres</a>
  </div>

  <div class="main-content">
    <h2 class="mb-4"><i class="bi bi-graph-up"></i> Liste des prêts avec intérêts</h2>

    <div class="d-flex gap-3 my-3" id="mode-toggle">
      <button type="button" class="btn toggle-btn active" data-mode="simple">Intérêt Simple</button>
      <button type="button" class="btn toggle-btn" data-mode="compose">Intérêt Composé</button>
      <button type="button" class="btn toggle-btn" data-mode="all">Les deux</button>
    </div>

    <div class="card-custom">
      <form id="filtreForm" class="d-flex flex-wrap gap-2 align-items-center">
        <label>Mois début:</label>
        <select id="mois_debut">
          <option value="01">Janvier</option><option value="02">Février</option>
          <option value="03">Mars</option><option value="04">Avril</option>
          <option value="05">Mai</option><option value="06">Juin</option>
          <option value="07">Juillet</option><option value="08">Août</option>
          <option value="09">Septembre</option><option value="10">Octobre</option>
          <option value="11">Novembre</option><option value="12">Décembre</option>
        </select>
        <label>Année début:</label>
        <input type="number" id="annee_debut" value="2020" />
        <label>Mois fin:</label>
        <select id="mois_fin">
          <option value="01">Janvier</option><option value="02">Février</option>
          <option value="03">Mars</option><option value="04">Avril</option>
          <option value="05">Mai</option><option value="06">Juin</option>
          <option value="07">Juillet</option><option value="08">Août</option>
          <option value="09">Septembre</option><option value="10">Octobre</option>
          <option value="11">Novembre</option><option value="12">Décembre</option>
        </select>
        <label>Année fin:</label>
        <input type="number" id="annee_fin" value="2050" />
        <button type="submit"><i class="bi bi-search"></i> Rechercher</button>
      </form>
    </div>

    <div class="card-custom">
      <table class="table">
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

    <div class="card-custom">
      <h5><i class="bi bi-bar-chart-line"></i> Intérêts mensuels</h5>
      <canvas id="interetChart"></canvas>
    </div>
  </div>

  <script>
    const apiBase = "http://localhost/projet_final/ws";

    function chargerInterets(mois_debut = null, annee_debut = null, mois_fin = null, annee_fin = null) {
      let url = apiBase + "/interets";
      if (mois_debut && annee_debut && mois_fin && annee_fin) {
        url += `?mois_debut=${mois_debut}&annee_debut=${annee_debut}&mois_fin=${mois_fin}&annee_fin=${annee_fin}`;
      }

      fetch(url)
        .then(res => res.json())
        .then(data => {
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

            if (mode === "simple" || mode === "compose") {
              tr.innerHTML = `
                <td>${p.nom_client}</td>
                <td>${parseFloat(p.montant).toLocaleString()} Ar</td>
                <td>${p.taux_interet} %</td>
                <td>${p.duree_mois} mois</td>
                <td>${p.mois}</td>
                <td><span style="color:${mode === 'simple' ? '#5c6ef8' : '#ff6b6b'};">
                  ${mode === 'simple' ? simpleTotal : composeTotal}</span></td>
                <td><span style="color:${mode === 'simple' ? '#5c6ef8' : '#ff6b6b'};">
                  ${mode === 'simple' ? simpleMensuel : composeMensuel}</span></td>`;
            } else {
              tr.innerHTML = `
                <td>${p.nom_client}</td>
                <td>${parseFloat(p.montant).toLocaleString()} Ar</td>
                <td>${p.taux_interet} %</td>
                <td>${p.duree_mois} mois</td>
                <td>${p.mois}</td>
                <td>
                  <div style="font-size:13px;">
                    <span style="color:#5c6ef8;">Simple :</span> ${simpleTotal}<br>
                    <span style="color:#ff6b6b;">Composé :</span> ${composeTotal}
                  </div>
                </td>
                <td>
                  <div style="font-size:13px;">
                    <span style="color:#5c6ef8;">Simple :</span> ${simpleMensuel}<br>
                    <span style="color:#ff6b6b;">Composé :</span> ${composeMensuel}
                  </div>
                </td>`;
            }

            tbody.appendChild(tr);

            const mois = p.mois;
            simples[mois] = (simples[mois] || 0) + parseFloat(p.interet_simple_mensuel);
            composes[mois] = (composes[mois] || 0) + parseFloat(p.interet_compose_mensuel);
          });

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
                  borderColor: "#5c6ef8",
                  backgroundColor: "rgba(92,110,248,0.2)",
                  tension: 0.4,
                  fill: true
                }] : []),
                ...(mode === 'compose' || mode === 'all' ? [{
                  label: "Intérêt composé",
                  data: labels.map(m => composes[m] || 0),
                  borderColor: "#ff6b6b",
                  backgroundColor: "rgba(255,107,107,0.2)",
                  tension: 0.4,
                  fill: true
                }] : [])
              ]
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: "Évolution des intérêts mensuels"
                }
              },
              scales: {
                y: { beginAtZero: true },
                x: { ticks: { autoSkip: true, maxTicksLimit: 12 } }
              }
            }
          });
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