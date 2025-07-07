<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des intérêts - Agent</title>
  <style>
    body { font-family: sans-serif; padding: 40px; }
    h1 { text-align: center; }
    form {
      margin-bottom: 20px;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #f2f2f2;
    }
    .total-row {
      font-weight: bold;
      background-color: #e6f7ff;
    }
    select, input[type="number"] {
      padding: 5px;
      margin: 5px;
    }
    button {
      padding: 6px 12px;
      margin-left: 10px;
    }
    canvas {
      margin-top: 30px;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <h1>Liste des prêts avec intérêts</h1>

  <form id="filtreForm">
    <label>Mois début :</label>
    <select id="mois_debut">
      <option value="01">Janvier</option><option value="02">Février</option>
      <option value="03">Mars</option><option value="04">Avril</option>
      <option value="05">Mai</option><option value="06">Juin</option>
      <option value="07">Juillet</option><option value="08">Août</option>
      <option value="09">Septembre</option><option value="10">Octobre</option>
      <option value="11">Novembre</option><option value="12">Décembre</option>
    </select>

    <label>Année début :</label>
    <input type="number" id="annee_debut" value="2025" />

    <label>Mois fin :</label>
    <select id="mois_fin">
      <option value="01">Janvier</option><option value="02">Février</option>
      <option value="03">Mars</option><option value="04">Avril</option>
      <option value="05">Mai</option><option value="06">Juin</option>
      <option value="07">Juillet</option><option value="08">Août</option>
      <option value="09">Septembre</option><option value="10">Octobre</option>
      <option value="11">Novembre</option><option value="12">Décembre</option>
    </select>

    <label>Année fin :</label>
    <input type="number" id="annee_fin" value="2025" />

    <button type="submit">🔍 Rechercher</button>
  </form>

  <table>
    <thead>
      <tr>
        <th>Client</th>
        <th>Montant</th>
        <th>Taux Annuel</th>
        <th>Durée (mois)</th>
        <th>Date</th>
        <th>Intérêt Total</th>
        <th>Intérêt Mensuel</th>
      </tr>
    </thead>
    <tbody id="table-interets"></tbody>
    <tfoot>
      <tr class="total-row">
        <td colspan="5">Total Intérêts Mensuels (Simples)</td>
        <td colspan="2" id="total-simple">0 Ar</td>
      </tr>
      <tr class="total-row">
        <td colspan="5">Total Intérêts Mensuels (Composés)</td>
        <td colspan="2" id="total-compose">0 Ar</td>
      </tr>
    </tfoot>
  </table>

  <h3 style="margin-top: 50px;">📊 Illustration du graphique des intérêts mensuels (Simple vs Composé)</h3>
  <canvas id="interetChart" width="800" height="300"></canvas>

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
          const tbody = document.getElementById("table-interets");
          tbody.innerHTML = "";
          let totalSimple = 0;
          let totalCompose = 0;

          const simples = {};
          const composes = {};

          data.forEach(p => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
              <td>${p.nom_client}</td>
              <td>${parseFloat(p.montant).toLocaleString()} Ar</td>
              <td>${p.taux_interet}%</td>
              <td>${p.duree_mois}</td>
              <td>${p.mois}</td>
              <td>
                <strong>Simple:</strong> ${parseFloat(p.interet_simple_total).toLocaleString()} Ar<br>
                <strong>Composé:</strong> ${parseFloat(p.interet_compose_total).toLocaleString()} Ar
              </td>
              <td>
                <strong>Simple:</strong> ${parseFloat(p.interet_simple_mensuel).toLocaleString()} Ar<br>
                <strong>Composé:</strong> ${parseFloat(p.interet_compose_mensuel).toLocaleString()} Ar
              </td>
            `;
            tbody.appendChild(tr);

            totalSimple += parseFloat(p.interet_simple_mensuel);
            totalCompose += parseFloat(p.interet_compose_mensuel);

            const mois = p.mois;
            simples[mois] = (simples[mois] || 0) + parseFloat(p.interet_simple_mensuel);
            composes[mois] = (composes[mois] || 0) + parseFloat(p.interet_compose_mensuel);
          });

          document.getElementById("total-simple").textContent = totalSimple.toLocaleString(undefined, {minimumFractionDigits: 2}) + " Ar";
          document.getElementById("total-compose").textContent = totalCompose.toLocaleString(undefined, {minimumFractionDigits: 2}) + " Ar";

          const labels = [...new Set([...Object.keys(simples), ...Object.keys(composes)])].sort();

          const ctx = document.getElementById("interetChart").getContext("2d");
          if (window.myChart) window.myChart.destroy();

          window.myChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: labels,
              datasets: [
                {
                  label: "Intérêt simple",
                  data: labels.map(mois => simples[mois] || 0),
                  borderColor: "orange",
                  backgroundColor: "orange",
                  fill: false,
                  tension: 0.3
                },
                {
                  label: "Intérêt composé",
                  data: labels.map(mois => composes[mois] || 0),
                  borderColor: "orangered",
                  backgroundColor: "orangered",
                  fill: false,
                  tension: 0.3
                }
              ]
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: "Comparaison des intérêts mensuels (simple vs composé)"
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  title: {
                    display: true,
                    text: "Montant des intérêts (Ar)"
                  }
                },
                x: {
                  title: {
                    display: true,
                    text: "Mois"
                  }
                }
              }
            }
          });
        });
    }

    document.getElementById("filtreForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const mois_debut = document.getElementById("mois_debut").value;
      const annee_debut = document.getElementById("annee_debut").value;
      const mois_fin = document.getElementById("mois_fin").value;
      const annee_fin = document.getElementById("annee_fin").value;

      chargerInterets(mois_debut, annee_debut, mois_fin, annee_fin);
    });

    // Chargement initial
    window.onload = () => chargerInterets();
  </script>
</body>
</html>
