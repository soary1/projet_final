<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Agent - Banque</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
        }

        .stat-card h3 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }

        .content-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .section-title {
            font-size: 1.3rem;
            color: #333;
        }

        .refresh-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .refresh-btn:hover {
            background: #5a6fd8;
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

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-en-attente {
            background: #fff3cd;
            color: #856404;
        }

        .status-approuve {
            background: #d4edda;
            color: #155724;
        }

        .status-rejete {
            background: #f8d7da;
            color: #721c24;
        }

        .action-select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
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
    </style>
</head>
<body>
    <header class="header">
        <h1>Dashboard Agent</h1>
        <div class="user-info">
            <span>Bienvenue, <?= htmlspecialchars($user['nom']) ?></span>
            <span>(<?= htmlspecialchars($user['role_data']['matricule']) ?>)</span>
            <button class="logout-btn" onclick="logout()">Déconnexion</button>
        </div>
    </header>

    <div class="container">
        <div id="alerts"></div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Prêts</h3>
                <div class="value" id="total-prets">-</div>
            </div>
            <div class="stat-card">
                <h3>Prêts Approuvés</h3>
                <div class="value" id="prets-approuves">-</div>
            </div>
            <div class="stat-card">
                <h3>En Attente</h3>
                <div class="value" id="prets-en-attente">-</div>
            </div>
            <div class="stat-card">
                <h3>Montant Total</h3>
                <div class="value" id="montant-total">-</div>
            </div>
        </div>

        <!-- Liste des prêts -->
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">Mes Prêts</h2>
                <button class="refresh-btn" onclick="loadPrets()">Actualiser</button>
            </div>
            <div id="prets-table">
                <div class="loading">Chargement...</div>
            </div>
        </div>
    </div>

    <script>
        // Charger les statistiques
        async function loadStats() {
            try {
                const response = await fetch('/ws/agent/stats');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('total-prets').textContent = data.data.total_prets || 0;
                    document.getElementById('prets-approuves').textContent = data.data.prets_approuves || 0;
                    document.getElementById('prets-en-attente').textContent = data.data.prets_en_attente || 0;
                    document.getElementById('montant-total').textContent = new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'EUR'
                    }).format(data.data.montant_total || 0);
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        // Charger les prêts
        async function loadPrets() {
            try {
                const response = await fetch('/ws/agent/prets');
                const data = await response.json();
                
                if (data.success) {
                    renderPretsTable(data.data);
                }
            } catch (error) {
                console.error('Erreur:', error);
                document.getElementById('prets-table').innerHTML = '<div class="loading">Erreur de chargement</div>';
            }
        }

        // Afficher le tableau des prêts
        function renderPretsTable(prets) {
            if (prets.length === 0) {
                document.getElementById('prets-table').innerHTML = '<p>Aucun prêt trouvé</p>';
                return;
            }

            let html = `
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Type de Prêt</th>
                            <th>Montant</th>
                            <th>Date Demande</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            prets.forEach(pret => {
                const statusClass = `status-${pret.statut.replace(' ', '-')}`;
                html += `
                    <tr>
                        <td>
                            ${pret.client_nom}<br>
                            <small>${pret.client_email}</small>
                        </td>
                        <td>
                            ${pret.type_pret}<br>
                            <small>${pret.taux_interet}% - ${pret.duree_mois} mois</small>
                        </td>
                        <td>${new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR'}).format(pret.montant)}</td>
                        <td>${new Date(pret.date_demande).toLocaleDateString('fr-FR')}</td>
                        <td><span class="status-badge ${statusClass}">${pret.statut}</span></td>
                        <td>
                            ${pret.statut === 'en attente' ? `
                                <select class="action-select" onchange="updatePretStatus(${pret.id}, this.value)">
                                    <option value="">Choisir action</option>
                                    <option value="approuvé">Approuver</option>
                                    <option value="rejeté">Rejeter</option>
                                </select>
                            ` : '-'}
                        </td>                        </tr>
                `;
            });

            html += '</tbody></table>';
            document.getElementById('prets-table').innerHTML = html;
        }

        // Mettre à jour le statut d'un prêt
        async function updatePretStatus(pretId, nouveauStatut) {
            if (!nouveauStatut) return;

            try {
                const formData = new FormData();
                formData.append('pret_id', pretId);
                formData.append('statut', nouveauStatut);

                const response = await fetch('/ws/agent/pret/update-status', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    showAlert('Statut mis à jour avec succès', 'success');
                    loadPrets();
                    loadStats();
                } else {
                    showAlert(data.message || 'Erreur lors de la mise à jour', 'error');
                }
            } catch (error) {
                showAlert('Erreur de connexion', 'error');
            }
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
                window.location.href = '/ws/agent/login';
            } catch (error) {
                window.location.href = '/ws/agent/login';
            }
        }

        // Charger les données au démarrage
        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
            loadPrets();
        });
    </script>
</body>
</html>

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .welcome-section h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .welcome-section p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .stat-card h3 {
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .stat-card p {
            color: #666;
            font-size: 14px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .action-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-5px);
        }

        .action-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-card p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }

        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }

            .user-info {
                flex-direction: column;
                gap: 10px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <h1>🏦 Banque - Espace Agent</h1>
            </div>
            <div class="user-info">
                <span>Bienvenue, <?= htmlspecialchars($agent_nom ?? 'Agent') ?></span>
                <span>Matricule: <?= htmlspecialchars($agent_matricule ?? 'N/A') ?></span>
                <a href="/auth/logout" class="logout-btn">Déconnexion</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="welcome-section">
            <h2>Tableau de bord</h2>
            <p>Gérez les demandes de prêts, consultez les fonds disponibles et supervisez les activités de la banque depuis votre espace agent.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon">📋</div>
                <h3><?= $stats['prets_en_attente'] ?? '0' ?></h3>
                <p>Prêts en attente</p>
            </div>
            <div class="stat-card">
                <div class="icon">✅</div>
                <h3><?= $stats['prets_valides'] ?? '0' ?></h3>
                <p>Prêts validés</p>
            </div>
            <div class="stat-card">
                <div class="icon">💰</div>
                <h3><?= number_format($stats['total_fonds'] ?? 0, 0, ',', ' ') ?> FCFA</h3>
                <p>Fonds disponibles</p>
            </div>
            <div class="stat-card">
                <div class="icon">👥</div>
                <h3><?= $stats['total_clients'] ?? '0' ?></h3>
                <p>Clients actifs</p>
            </div>
        </div>

        <div class="actions-grid">
            <div class="action-card">
                <h3>
                    <span>📋</span>
                    Gestion des Prêts
                </h3>
                <p>Examinez et traitez les demandes de prêts en attente. Validez ou refusez les demandes selon les critères de la banque.</p>
                <a href="/agents/prets" class="btn">Gérer les prêts</a>
            </div>

            <div class="action-card">
                <h3>
                    <span>💰</span>
                    Gestion des Fonds
                </h3>
                <p>Consultez et gérez les fonds de la banque. Ajoutez de nouveaux fonds selon les besoins.</p>
                <a href="/agents/fonds" class="btn">Gérer les fonds</a>
            </div>

            <div class="action-card">
                <h3>
                    <span>👥</span>
                    Clients
                </h3>
                <p>Consultez la liste des clients et leurs informations. Suivez l'historique de leurs demandes.</p>
                <a href="/agents/clients" class="btn btn-secondary">Voir les clients</a>
            </div>

            <div class="action-card">
                <h3>
                    <span>📊</span>
                    Rapports
                </h3>
                <p>Générez des rapports détaillés sur les activités de la banque et les performances.</p>
                <a href="/agents/rapports" class="btn btn-secondary">Voir les rapports</a>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Banque. Tous droits réservés. | Espace Agent Sécurisé</p>
    </footer>

    <script>
        // Animation d'entrée pour les cartes
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card, .action-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>
