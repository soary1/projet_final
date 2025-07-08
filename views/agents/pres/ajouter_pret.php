<?php
session_start();

// ⚠️ Sécurité : redirige si l'agent n'est pas connecté
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'agent') {
    header("Location: /projet_final/views/agents/login.php");
    exit;
}

$id_agent = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un Prêt</title>
  <style>
    body { font-family: sans-serif; padding: 20px; }
    input, select, button { margin: 5px; padding: 5px; }
  </style>
</head>
<body>
  <h1>Ajouter un Prêt</h1>

  <div>
    <select id="id_client">
      <option value="">-- Choisir un client --</option>
    </select>

    <select id="id_type_pret"></select>

    <input type="number" id="montant" placeholder="Montant">

    <button onclick="ajouterPret()">Soumettre</button>
    <a href="liste_prets.html">Voir mes prêts</a>
  </div>

  <script>
    const apiBase = "http://localhost/projet_final/ws";
    const idAgent = <?= json_encode($id_agent) ?>; // Injecté côté PHP

    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            callback && callback(JSON.parse(xhr.responseText));
          } else {
            alert("Erreur : " + xhr.status);
          }
        }
      };
      xhr.send(data);
    }

    function chargerClients() {
      ajax("GET", "/clients", null, (data) => {
        const select = document.getElementById("id_client");
        data.forEach(c => {
          const opt = document.createElement("option");
          opt.value = c.id;
          opt.textContent = `${c.nom_utilisateur} (ID ${c.id})`;
          select.appendChild(opt);
        });
      });
    }

    function chargerTypesPret() {
      ajax("GET", "/typepret", null, (data) => {
        const select = document.getElementById("id_type_pret");
        data.forEach(t => {
          const opt = document.createElement("option");
          opt.value = t.id;
          opt.textContent = t.nom;
          select.appendChild(opt);
        });
      });
    }

    function ajouterPret() {
      const id_client = document.getElementById("id_client").value;
      const id_type_pret = document.getElementById("id_type_pret").value;
      const montant = document.getElementById("montant").value;

      if (!id_client || !id_type_pret || !montant) {
        alert("Veuillez remplir tous les champs.");
        return;
      }

      const data = `id_client=${id_client}&id_agent=${idAgent}&id_type_pret=${id_type_pret}&montant=${montant}`;

      ajax("POST", `/prets`, data, () => {
        alert("Prêt soumis avec succès !");
        document.getElementById("id_client").value = "";
        document.getElementById("id_type_pret").value = "";
        document.getElementById("montant").value = "";
      });
    }

    chargerClients();
    chargerTypesPret();
  </script>
</body>
</html>
