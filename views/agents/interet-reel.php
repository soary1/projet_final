<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Intérêts Réels Gagnés</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
      background-color: #f4f4f4;
    }
    h1 {
      text-align: center;
      color: #2c3e50;
    }
    form {
      text-align: center;
      margin-bottom: 20px;
    }
    select {
      padding: 6px;
      margin: 0 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      margin-top: 30px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #2980b9;
      color: white;
    }
  </style>
</head>
<body>

<h1>📊 Intérêts Réels Gagnés par Mois</h1>

<form id="filtreForm">
  <label>De :</label>
  <select id="moisDebut"></select>
  <select id="anneeDebut"></select>

  <label>à :</label>
  <select id="moisFin"></select>
  <select id="anneeFin"></select>

  <button type="submit">Filtrer</button>
</form>

<canvas id="graphique" height="100"></canvas>

<table id="tableauResultats" style="display:none;">
  <thead>
    <tr>
      <th>Mois</th>
      <th>Intérêt Gagné (Ar)</th>
      <th>Nombre de Remboursements</th>
      <th>En Retard</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<script>
  const moisOptions = [
    "01", "02", "03", "04", "05", "06",
    "07", "08", "09", "10", "11", "12"
  ];

  function remplirSelects() {
    const now = new Date();
    const anneeCourante = now.getFullYear();

    for (let i = 0; i < 12; i++) {
      const m = moisOptions[i];
      document.getElementById("moisDebut").innerHTML += `<option value="${m}">${m}</option>`;
      document.getElementById("moisFin").innerHTML += `<option value="${m}">${m}</option>`;
    }

    for (let y = anneeCourante - 5; y <= anneeCourante + 2; y++) {
      document.getElementById("anneeDebut").innerHTML += `<option value="${y}">${y}</option>`;
      document.getElementById("anneeFin").innerHTML += `<option value="${y}">${y}</option>`;
    }
  }

  document.getElementById("filtreForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const mois_debut = document.getElementById("moisDebut").value;
    const annee_debut = document.getElementById("anneeDebut").value;
    const mois_fin = document.getElementById("moisFin").value;
    const annee_fin = document.getElementById("anneeFin").value;

    fetch(`http://localhost/projet_final/ws/interets-reels?mois_debut=${mois_debut}&annee_debut=${annee_debut}&mois_fin=${mois_fin}&annee_fin=${annee_fin}`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          afficherResultats(data.data);
        } else {
          alert(data.message || "Erreur !");
        }
      });
  });

  let chart = null;

  function afficherResultats(donnees) {
    const tbody = document.querySelector("#tableauResultats tbody");
    tbody.innerHTML = "";
    document.getElementById("tableauResultats").style.display = "table";

    const labels = [];
    const valeurs = [];

    donnees.forEach(ligne => {
      labels.push(ligne.mois_paiement);
      valeurs.push(parseFloat(ligne.interet_mensuel_gagne));

      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${ligne.mois_paiement}</td>
        <td>${parseFloat(ligne.interet_mensuel_gagne).toLocaleString()} Ar</td>
        <td>${ligne.nb_remboursements}</td>
        <td>${ligne.nb_retards}</td>
      `;
      tbody.appendChild(tr);
    });

    const ctx = document.getElementById("graphique").getContext("2d");

    if (chart !== null) chart.destroy();

    chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Intérêt mensuel gagné (Ar)',
          data: valeurs,
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }

  window.onload = remplirSelects;
</script>

</body>
</html>
