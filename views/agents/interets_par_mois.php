<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Suivi des Intérêts - Banque</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 40px; }
    h1 { text-align: center; }
    form { text-align: center; margin-bottom: 30px; }
    select, button { padding: 6px; margin: 0 10px; }
    canvas { margin-top: 30px; }
    table { width: 100%; border-collapse: collapse; margin-top: 30px; background: white; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    th { background-color: #2980b9; color: white; }
  </style>
</head>
<body>

<h1>📅 Intérêts Prévus vs Réels</h1>

<form id="filtreForm">
  <label>De :</label>
  <select id="moisDebut"></select>
  <select id="anneeDebut"></select>

  <label>à :</label>
  <select id="moisFin"></select>
  <select id="anneeFin"></select>

  <button type="submit">Afficher</button>
</form>

<canvas id="graphique" height="100"></canvas>

<table id="resultatTable" style="display:none;">
  <thead>
    <tr>
      <th>Mois</th>
      <th>Intérêt Prévu (Ar)</th>
      <th>Intérêt Réel (Ar)</th>
      <th>Nb Remboursements</th>
      <th>Nb Retards</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<script>
  const moisOptions = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];

  function remplirSelects() {
    const now = new Date();
    const annee = now.getFullYear();
    for (let m of moisOptions) {
      document.getElementById("moisDebut").innerHTML += `<option value="${m}">${m}</option>`;
      document.getElementById("moisFin").innerHTML += `<option value="${m}">${m}</option>`;
    }
    for (let y = annee - 5; y <= annee + 1; y++) {
      document.getElementById("anneeDebut").innerHTML += `<option value="${y}">${y}</option>`;
      document.getElementById("anneeFin").innerHTML += `<option value="${y}">${y}</option>`;
    }
  }

  let chart = null;

  document.getElementById("filtreForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const mois_debut = document.getElementById("moisDebut").value;
    const annee_debut = document.getElementById("anneeDebut").value;
    const mois_fin = document.getElementById("moisFin").value;
    const annee_fin = document.getElementById("anneeFin").value;

    fetch(`http://localhost/projet_final/ws/interets-par-mois?mois_debut=${mois_debut}&annee_debut=${annee_debut}&mois_fin=${mois_fin}&annee_fin=${annee_fin}`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          afficherGraphique(data.data);
        } else {
          alert(data.message || "Erreur serveur");
        }
      });
  });

  function afficherGraphique(donnees) {
    const tbody = document.querySelector("#resultatTable tbody");
    tbody.innerHTML = "";
    document.getElementById("resultatTable").style.display = "table";

    const labels = [], prevus = [], reels = [];

    donnees.forEach(ligne => {
      labels.push(ligne.mois);
      prevus.push(parseFloat(ligne.interet_prevu));
      reels.push(parseFloat(ligne.interet_reel));

      tbody.innerHTML += `
        <tr>
          <td>${ligne.mois}</td>
          <td>${parseFloat(ligne.interet_prevu).toLocaleString()} Ar</td>
          <td>${parseFloat(ligne.interet_reel).toLocaleString()} Ar</td>
          <td>${ligne.nb_remboursements}</td>
          <td>${ligne.nb_retards}</td>
        </tr>
      `;
    });

    const ctx = document.getElementById("graphique").getContext("2d");
    if (chart) chart.destroy();

    chart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Intérêt Prévu",
            data: prevus,
            backgroundColor: "#3498db"
          },
          {
            label: "Intérêt Réel",
            data: reels,
            backgroundColor: "#27ae60"
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  }

  window.onload = remplirSelects;
</script>

</body>
</html>
