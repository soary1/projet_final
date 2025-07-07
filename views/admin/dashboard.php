<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Banque</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 1.8rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .tabs {
            display: flex;
            background: white;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 0;
        }

        .tab {
            flex: 1;
            padding: 1rem 2rem;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            border-radius: 10px 10px 0 0;
        }

        .tab.active {
            background: white;
            color: #e74c3c;
            font-weight: 600;
        }

        .tab-content {
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 2rem;
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }

        .stat-card h3 {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 0.9rem;
        }

        .btn:hover {
            background: #c0392b;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .alert {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 5px;
            display: none;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Dashboard Administrateur</h1>
        <div class="user-info">
            <span>Bienvenue, <?= htmlspecialchars($user['nom']) ?></span>
            <span>(<?= htmlspecialchars($user['role_data']['niveau_acces']) ?>)</span>
            <button class="logout-btn" onclick="logout()">Déconnexion</button>
        </div>
    </header>

    <div class="container">
        <div id="alerts"></div>

        <!-- Onglets -->
        <div class="tabs">
            <button class="tab active" onclick="showTab('overview')">Vue d'ensemble</button>
            <button class="tab" onclick="showTab('agents')">Agents</button>
            <button class="tab" onclick="showTab('prets')">Prêts</button>
            <button class="tab" onclick="showTab('types-prets')">Types de Prêts</button>
            <button class="tab" onclick="showTab('fonds')">Fonds</button>
        </div>

        <!-- Contenu des onglets -->
        <div id="overview" class="tab-content active">
            <h2>Statistiques Globales</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Utilisateurs</h3>
                    <div class="value" id="total-utilisateurs">-</div>
                </div>
                <div class="stat-card">
                    <h3>Agents</h3>
                    <div class="value" id="total-agents">-</div>
                </div>
                <div class="stat-card">
                    <h3>Clients</h3>
                    <div class="value" id="total-clients">-</div>
                </div>
                <div class="stat-card">
                    <h3>Prêts Totaux</h3>
                    <div class="value" id="total-prets">-</div>
                </div>
                <div class="stat-card">
                    <h3>Montant Total</h3>
                    <div class="value" id="montant-total">-</div>
                </div>
                <div class="stat-card">
                    <h3>Fonds Disponibles</h3>
                    <div class="value" id="total-fonds">-</div>
                </div>
            </div>
        </div>

        <div id="agents" class="tab-content">
            <div class="section-header">
                <h2>Gestion des Agents</h2>
                <button class="btn" onclick="showModal('agent-modal')">Ajouter Agent</button>
            </div>
            <div id="agents-table">
                <div class="loading">Chargement...</div>
            </div>
        </div>

        <div id="prets" class="tab-content">
            <div class="section-header">
                <h2>Tous les Prêts</h2>
                <button class="btn btn-secondary" onclick="loadAllPrets()">Actualiser</button>
            </div>
            <div id="prets-table">
                <div class="loading">Chargement...</div>
            </div>
        </div>

        <div id="types-prets" class="tab-content">
            <div class="section-header">
                <h2>Types de Prêts</h2>
                <button class="btn" onclick="showModal('type-pret-modal')">Ajouter Type</button>
            </div>
            <div id="types-prets-table">
                <div class="loading">Chargement...</div>
            </div>
        </div>

        <div id="fonds" class="tab-content">
            <div class="section-header">
                <h2>Gestion des Fonds</h2>
                <button class="btn" onclick="showModal('fond-modal')">Ajouter Fonds</button>
            </div>
            <p>Fonctionnalité de gestion des fonds...</p>
        </div>
    </div>

    <!-- Modals -->
    <div id="agent-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="hideModal('agent-modal')">&times;</span>
            <h2>Ajouter un Agent</h2>
            <form id="agent-form" onsubmit="createAgent(event)">
                <div class="form-group">
                    <label for="agent-nom">Nom complet</label>
                    <input type="text" id="agent-nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="agent-email">Email</label>
                    <input type="email" id="agent-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="agent-password">Mot de passe</label>
                    <input type="password" id="agent-password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="agent-matricule">Matricule</label>
                    <input type="text" id="agent-matricule" name="matricule" required>
                </div>
                <button type="submit" class="btn">Créer Agent</button>
            </form>
        </div>
    </div>

    <div id="type-pret-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="hideModal('type-pret-modal')">&times;</span>
            <h2>Ajouter un Type de Prêt</h2>
            <form id="type-pret-form" onsubmit="createTypePret(event)">
                <div class="form-group">
                    <label for="type-nom">Nom du type</label>
                    <input type="text" id="type-nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="type-taux">Taux d'intérêt (%)</label>
                    <input type="number" step="0.01" id="type-taux" name="taux_interet" required>
                </div>
                <div class="form-group">
                    <label for="type-duree">Durée (mois)</label>
                    <input type="number" id="type-duree" name="duree_mois" required>
                </div>
                <button type="submit" class="btn">Créer Type</button>
            </form>
        </div>
    </div>

    <script>
        // Gestion des onglets
        function showTab(tabName) {
            // Masquer tous les contenus
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Désactiver tous les onglets
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Activer l'onglet et le contenu sélectionnés
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
            
            // Charger les données spécifiques à l'onglet
            switch(tabName) {
                case 'overview':
                    loadGlobalStats();
                    break;
                case 'agents':
                    loadAgents();
                    break;
                case 'prets':
                    loadAllPrets();
                    break;
                case 'types-prets':
                    loadTypesPrets();
                    break;
            }
        }

        // Charger les statistiques globales
        async function loadGlobalStats() {
            try {
                const response = await fetch('/ws/admin/stats');
                const data = await response.json();
                
                if (data.success) {
                    const stats = data.data;
                    document.getElementById('total-utilisateurs').textContent = stats.total_utilisateurs || 0;
                    document.getElementById('total-agents').textContent = stats.total_agents || 0;
                    document.getElementById('total-clients').textContent = stats.total_clients || 0;
                    document.getElementById('total-prets').textContent = stats.total_prets || 0;
                    document.getElementById('montant-total').textContent = new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'EUR'
                    }).format(stats.montant_total_approuve || 0);
                    document.getElementById('total-fonds').textContent = new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'EUR'
                    }).format(stats.total_fonds || 0);
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        // Charger les agents
        async function loadAgents() {
            try {
                const response = await fetch('/ws/admin/agents');
                const data = await response.json();
                
                if (data.success) {
                    renderAgentsTable(data.data);
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        // Afficher le tableau des agents
        function renderAgentsTable(agents) {
            let html = `
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Matricule</th>
                            <th>Date Embauche</th>
                            <th>Nb Prêts</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            agents.forEach(agent => {
                html += `
                    <tr>
                        <td>${agent.nom}</td>
                        <td>${agent.email}</td>
                        <td>${agent.matricule}</td>
                        <td>${new Date(agent.date_embauche).toLocaleDateString('fr-FR')}</td>
                        <td>${agent.nombre_prets}</td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            document.getElementById('agents-table').innerHTML = html;
        }

        // Charger tous les prêts
        async function loadAllPrets() {
            try {
                const response = await fetch('/ws/admin/prets');
                const data = await response.json();
                
                if (data.success) {
                    renderPretsTable(data.data);
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        // Afficher le tableau des prêts
        function renderPretsTable(prets) {
            let html = `
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Agent</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            prets.forEach(pret => {
                html += `
                    <tr>
                        <td>${pret.client_nom}</td>
                        <td>${pret.agent_nom}</td>
                        <td>${pret.type_pret}</td>
                        <td>${new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR'}).format(pret.montant)}</td>
                        <td>${new Date(pret.date_demande).toLocaleDateString('fr-FR')}</td>
                        <td>${pret.statut}</td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            document.getElementById('prets-table').innerHTML = html;
        }

        // Charger les types de prêts
        async function loadTypesPrets() {
            try {
                const response = await fetch('/ws/admin/types-prets');
                const data = await response.json();
                
                if (data.success) {
                    renderTypesPretsTable(data.data);
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        // Afficher le tableau des types de prêts
        function renderTypesPretsTable(types) {
            let html = `
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Taux d'intérêt</th>
                            <th>Durée (mois)</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            types.forEach(type => {
                html += `
                    <tr>
                        <td>${type.nom}</td>
                        <td>${type.taux_interet}%</td>
                        <td>${type.duree_mois}</td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            document.getElementById('types-prets-table').innerHTML = html;
        }

        // Créer un agent
        async function createAgent(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            
            try {
                const response = await fetch('/ws/admin/agents', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('Agent créé avec succès', 'success');
                    hideModal('agent-modal');
                    loadAgents();
                    event.target.reset();
                } else {
                    showAlert(data.message || 'Erreur lors de la création', 'error');
                }
            } catch (error) {
                showAlert('Erreur de connexion', 'error');
            }
        }

        // Créer un type de prêt
        async function createTypePret(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            
            try {
                const response = await fetch('/ws/admin/types-prets', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('Type de prêt créé avec succès', 'success');
                    hideModal('type-pret-modal');
                    loadTypesPrets();
                    event.target.reset();
                } else {
                    showAlert(data.message || 'Erreur lors de la création', 'error');
                }
            } catch (error) {
                showAlert('Erreur de connexion', 'error');
            }
        }

        // Gestion des modals
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function hideModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Afficher une alerte
        function showAlert(message, type) {
            const alertsContainer = document.getElementById('alerts');
            const alert = document.createElement('div');
            alert.className = `alert ${type}`;
            alert.textContent = message;
            alert.style.display = 'block';
            
            alertsContainer.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }

        // Déconnexion
        async function logout() {
            try {
                const response = await fetch('/ws/logout', {method: 'POST'});
                window.location.href = '/ws/admin/login';
            } catch (error) {
                window.location.href = '/ws/admin/login';
            }
        }

        // Charger les données au démarrage
        document.addEventListener('DOMContentLoaded', function() {
            loadGlobalStats();
        });

        // Fermer les modals en cliquant à l'extérieur
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
