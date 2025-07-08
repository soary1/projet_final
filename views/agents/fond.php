<?php
session_start();


if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] !== 'agent') {
    header("Location: /projet_final/views/agents/login.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un fond - Agent</title>
  <style>
    body { font-family: sans-serif; padding: 50px; background-color: #f8f8f8; }
    h1 { text-align: center; }
    form {
      max-width: 500px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input, select, button {
      display: block;
      width: 100%;
      margin-bottom: 15px;
      padding: 10px;
      font-size: 16px;
    }
  </style>
</head>
<body>

  <h1>Ajouter un fond</h1>

  <form onsubmit="ajouterFond(event)">
    <input type="number" id="montant" placeholder="Montant" required step="0.01">
    
    <select id="id_type_fond" required>
      <option value="">-- Type de fond --</option>
    </select>

    <input type="hidden" id="id_agent" placeholder="ID Agent" value="1">

    <button type="submit">Ajouter</button>
  </form>

  <script>
    const apiBase = "http://localhost/projet_final/ws";


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
        .then(res => res.json())
        .then(data => {
          const select = document.getElementById("id_type_fond");
          data.forEach(t => {
            const opt = document.createElement("option");
            opt.value = t.id;
            opt.textContent = t.nom;
            select.appendChild(opt);
          });
        });
    }

    function ajouterFond(event) {
      event.preventDefault();
      const montant = document.getElementById("montant").value;
      const id_type_fond = document.getElementById("id_type_fond").value;
      const id_agent = document.getElementById("id_agent").value;

      const data = `montant=${encodeURIComponent(montant)}&id_type_fond=${id_type_fond}&id_agent=${id_agent}`;

      fetch(apiBase + "/fond", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: data
      })
      .then(res => res.json())
      .then(resp => {
        alert(resp.message);
        document.querySelector("form").reset();
      });
    }
// chargerAgentDepuisSession();
    chargerTypesFond();
  </script>

</body>
</html>
