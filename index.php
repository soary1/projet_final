<?php
require '/ws/vendor/autoload.php';
Flight::set('flight.base_url', '/projet_final');
$baseUrl = Flight::get('flight.base_url');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil - Banque</title>
  <style>
    body {
      font-family: sans-serif;
      background-color: #f4f4f4;
      padding: 40px;
      text-align: center;
    }
    h1 {
      margin-bottom: 20px;
    }
    a {
      display: inline-block;
      margin: 10px;
      padding: 12px 25px;
      background-color: #3498db;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-size: 18px;
    }
    a:hover {
      background-color: #2980b9;
    }

    .admin-section {
      margin-top: 40px;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    input, button {
      padding: 8px;
      margin: 8px;
      font-size: 16px;
    }

    table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 8px;
    }

    th {
      background-color: #eee;
    }
  </style>
</head>
<body>

  <h1>Bienvenue dans le système bancaire</h1>

  <a href="<?= $baseUrl ?>/client.html">Client</a>
  <a href="<?= $baseUrl ?>views/agent/home.html">Agent</a>
  <a href="<?= $baseUrl ?>/admin.html">Admin</a>

  <div class="admin-section">
    <h2>Gestion des Types de Prêt (Admin)</h2>
    <input type="text" id="nom_type" placeholder="Nom du prêt">
    <input type="number" step="0.01" id="taux" placeholder="Taux (%)">
    <button onclick="ajouterTypePret()">Ajouter</button>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Taux (%)</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="table-type-pret">
      </tbody>
    </table>
  </div>

  <script>
    const apiBase = "http://localhost/projet_final/ws";

    function chargerTypesPret() {
      fetch(apiBase + "/typepret")
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById("table-type-pret");
          tbody.innerHTML = "";
          data.forEach(tp => {
            const row = document.createElement("tr");
            row.innerHTML = `
              <td>${tp.id}</td>
              <td>${tp.nom}</td>
              <td>${tp.taux_interet}</td>
              <td><button onclick="supprimerTypePret(${tp.id})">🗑️</button></td>
            `;
            tbody.appendChild(row);
          });
        });
    }

    function ajouterTypePret() {
      const nom = document.getElementById("nom_type").value;
      const taux = document.getElementById("taux").value;

      const data = `nom=${encodeURIComponent(nom)}&taux_interet=${encodeURIComponent(taux)}`;

      fetch(apiBase + "/typepret", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: data
      })
      .then(res => res.json())
      .then(() => {
        document.getElementById("nom_type").value = "";
        document.getElementById("taux").value = "";
        chargerTypesPret();
      });
    }

    function supprimerTypePret(id) {
      if (!confirm("Supprimer ce type de prêt ?")) return;

      fetch(apiBase + `/typepret/${id}`, {
        method: "DELETE"
      })
      .then(() => chargerTypesPret());
    }

    // Initialisation
    chargerTypesPret();
  </script>

</body>
</html>
