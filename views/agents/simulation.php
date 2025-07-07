<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Simulation de prêt</title>
  <style>
    body { font-family: sans-serif; padding: 40px; background: #f9f9f9; }
    h1 { text-align: center; }
    form, table { margin: auto; max-width: 800px; }
    form { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
    input, select { width: 200px; padding: 6px; margin: 10px; }
    button { padding: 8px 16px; margin-top: 10px; }
    table { margin-top: 30px; width: 100%; border-collapse: collapse; background: white; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
    th { background-color: #f0f0f0; }
  </style>
</head>
<body>

  <h1>💼 Simulation de prêts</h1>

  <form id="simulationForm">
    <label>Montant (Ar) :</label>
    <input type="number" step="1000" id="montant" required>

    <label>Taux Annuel (%) :</label>
    <input type="number" step="0.01" id="taux" required>

    <label>Durée (mois) :</label>
    <input type="number" id="duree" required>

    <button type="submit">Ajouter à la simulation</button>
  </form>

  <form id="validationForm" style="display:none; margin-top:20px;">
    <h3>✅ Valider les prêts simulés dans la base</h3>
    <label>Client :</label>
    <select id="clientSelect" name="id_client" required></select>

    <label>Agent :</label>
    <select id="agentSelect" name="id_agent" required></select>

    <button type="submit">✅ Enregistrer les prêts simulés</button>
  </form>

  <table id="resultatTable" style="display:none;">
    <thead>
      <tr>
        <th>#</th>
        <th>Montant</th>
        <th>Taux (%)</th>
        <th>Durée (mois)</th>
        <th>Intérêt Simple Total</th>
        <th>Mensuel Simple</th>
        <th>Intérêt Composé Total</th>
        <th>Mensuel Composé</th>
      </tr>
    </thead>
    <tbody id="resultats"></tbody>
  </table>

  <script>
    const simulations = [];

    function chargerClientsEtAgents() {
      fetch("http://localhost/projet_final/ws/clients-agents")
        .then(res => res.json())
        .then(data => {
          const clientSelect = document.getElementById("clientSelect");
          const agentSelect = document.getElementById("agentSelect");

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
        });
    }

    document.getElementById("simulationForm").addEventListener("submit", function(e) {
      e.preventDefault();

      const montant = parseFloat(document.getElementById("montant").value);
      const taux = parseFloat(document.getElementById("taux").value);
      const duree = parseInt(document.getElementById("duree").value);
      const taux_mensuel = taux / 100 / 12;

      // Intérêt simple
      const interet_simple_total = montant * (taux / 100) * (duree / 12);
      const interet_simple_mensuel = interet_simple_total / duree;

      // Intérêt composé (formule d’annuité constante)
      let interet_compose_total = 0;
      let interet_compose_mensuel = 0;
      if (taux_mensuel > 0 && duree > 0) {
        const mensualite = montant * taux_mensuel / (1 - Math.pow(1 + taux_mensuel, -duree));
        const total_rembourse = mensualite * duree;
        interet_compose_total = total_rembourse - montant;
        interet_compose_mensuel = interet_compose_total / duree;
      }

      simulations.push({ montant, taux, duree });

      afficherResultats();
    });

    function afficherResultats() {
      const tbody = document.getElementById("resultats");
      tbody.innerHTML = "";
      document.getElementById("resultatTable").style.display = "table";
      document.getElementById("validationForm").style.display = "block";

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

        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${index + 1}</td>
          <td>${s.montant.toLocaleString()} Ar</td>
          <td>${s.taux}%</td>
          <td>${s.duree}</td>
          <td>${interet_simple_total.toFixed(2).toLocaleString()} Ar</td>
          <td>${interet_simple_mensuel.toFixed(2).toLocaleString()} Ar</td>
          <td>${interet_compose_total.toFixed(2).toLocaleString()} Ar</td>
          <td>${interet_compose_mensuel.toFixed(2).toLocaleString()} Ar</td>
        `;
        tbody.appendChild(tr);
      });
    }

    document.getElementById("validationForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const id_client = document.getElementById("clientSelect").value;
    const id_agent = document.getElementById("agentSelect").value;

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
        if (confirm("Aucun type de prêt similaire trouvé. Voulez-vous l’ajouter dans la base ?")) {
            // Ajouter tous les types manquants
            simulations.forEach(p => {
                fetch("http://localhost/projet_final/ws/typepret", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                    nom: `Auto-${p.taux}%/${p.duree}m`,
                    taux_interet: p.taux,
                    duree_mois: p.duree
                    })
                });
            });

            alert("Type de prêt ajouté. Cliquez à nouveau sur 'Valider' pour réessayer.");
        }
        } else {
        alert(res.message || "Simulation enregistrée avec succès !");
        simulations.length = 0;
        afficherResultats();
        }
    })
    .catch(err => alert("Erreur lors de l'enregistrement"));
    });


    window.onload = () => {
      chargerClientsEtAgents();
    };
  </script>

</body>
</html>
