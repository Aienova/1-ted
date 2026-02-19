<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Entreprises</title>
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

        .companies-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .page-header h1 {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .page-header p {
            font-size: 1rem;
            opacity: 0.95;
        }

        /* Filtres */
        .filters-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
        }

        .filter-group input,
        .filter-group select {
            padding: 10px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .filters-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-reset {
            padding: 10px 20px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            color: #6b7280;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            border-color: #667eea;
            color: #667eea;
            background: #f9fafb;
        }

        /* Compteur et résultats */
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .results-count {
            font-size: 1.1rem;
            color: #374151;
        }

        .results-count strong {
            color: #667eea;
            font-size: 1.4rem;
        }

        /* Tableau */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .companies-table {
            width: 100%;
            border-collapse: collapse;
        }

        .companies-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .companies-table th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .companies-table th.actions-col {
            text-align: center;
            width: 180px;
        }

        .companies-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.2s ease;
        }

        .companies-table tbody tr:hover {
            background: #f9fafb;
        }

        .companies-table tbody tr:last-child {
            border-bottom: none;
        }

        .companies-table td {
            padding: 15px 20px;
            color: #374151;
        }

        .company-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 1.05rem;
        }

        .company-domain {
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 3px;
        }

        .company-location {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #6b7280;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .stat-value {
            font-weight: 600;
            color: #667eea;
        }

        /* Actions */
        .actions-cell {
            text-align: center;
        }

        .actions-dropdown {
            position: relative;
            display: inline-block;
        }

        .btn-actions {
            padding: 8px 16px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-actions:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            min-width: 220px;
            margin-top: 8px;
            z-index: 1000;
            overflow: hidden;
        }

        .dropdown-menu.active {
            display: block;
            animation: slideDown 0.2s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 12px 18px;
            color: #374151;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.2s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 0.95rem;
        }

        .dropdown-item:hover {
            background: #f9fafb;
        }

        .dropdown-item.danger {
            color: #dc2626;
        }

        .dropdown-item.danger:hover {
            background: #fee2e2;
        }

        /* Loader */
        .loader {
            text-align: center;
            padding: 60px 20px;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e5e7eb;
            border-top-color: #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* État vide */
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
        }

        .no-results .icon {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .no-results h3 {
            color: #374151;
            margin-bottom: 10px;
        }

        .no-results p {
            color: #6b7280;
        }

        /* Notifications */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            max-width: 400px;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .notification.success {
            border-left: 4px solid #10b981;
        }

        .notification.error {
            border-left: 4px solid #ef4444;
        }

        .notification .icon {
            font-size: 1.5rem;
        }

        .notification .message {
            flex: 1;
            color: #374151;
        }

        .notification .close {
            cursor: pointer;
            color: #6b7280;
            font-size: 1.2rem;
            line-height: 1;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .companies-table {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .table-container {
                overflow-x: auto;
            }

            .companies-table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>
    <div class="companies-container">
        <!-- Header -->
        <div class="page-header">
            <h1>Gestion des Entreprises</h1>
            <p>Gérez toutes les entreprises enregistrées dans la plateforme</p>
        </div>

        <!-- Filtres -->
        <div class="filters-section">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="filter-keyword">Recherche</label>
                    <input 
                        type="text" 
                        id="filter-keyword" 
                        placeholder="Nom, domaine, localisation..."
                    >
                </div>
                <div class="filter-group">
                    <label for="filter-country">Pays</label>
                    <select id="filter-country">
                        <option value="">Tous les pays</option>
                        <option value="France">France</option>
                        <option value="Belgique">Belgique</option>
                        <option value="Suisse">Suisse</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="Canada">Canada</option>
                    </select>
                </div>
            </div>
            <div class="filters-actions">
                <button class="btn-reset" onclick="resetFilters()">
                    Réinitialiser
                </button>
            </div>
        </div>

        <!-- Résultats -->
        <div class="results-header">
            <div class="results-count">
                <strong id="companies-count">0</strong> entreprise(s) trouvée(s)
            </div>
        </div>

        <!-- Tableau -->
        <div class="table-container">
            <table class="companies-table">
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Localisation</th>
                        <th>Effectif</th>
                        <th>Offres</th>
                        <th>Comptes</th>
                        <th>Statut</th>
                        <th class="actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody id="companies-tbody">
                    <!-- Les entreprises seront chargées ici -->
                    <tr>
                        <td colspan="7">
                            <div class="loader">
                                <div class="spinner"></div>
                                <p>Chargement des entreprises...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Message vide (caché par défaut) -->
        <div class="no-results" id="no-results" style="display: none;">
            <div class="icon">🏢</div>
            <h3>Aucune entreprise trouvée</h3>
            <p>Aucune entreprise ne correspond aux critères de recherche.</p>
        </div>
    </div>

    <!-- Popup pour voir l'entreprise -->
    <?php
    $popupId = 'popup-company';
    $popupSize = 'large';
    $popupClass = '';
    include __DIR__ . '/blocks/popup.php';
    ?>

    <!-- Popup pour voir les offres d'emploi -->
    <?php
    $popupId = 'popup-jobs';
    $popupSize = 'large';
    $popupClass = '';
    include __DIR__ . '/blocks/popup.php';
    ?>

    <script src="<?php echo $baseUrl; ?>/script/popup.js"></script>
    <script>
        // Variables globales
        let companies = [];
        let activeDropdown = null;
        const baseUrl = '<?php echo $baseUrl; ?>';

        // Charger les entreprises au démarrage
        document.addEventListener('DOMContentLoaded', () => {
            loadCompanies();
            setupFilters();
        });

        // Configurer les filtres avec debounce
        function setupFilters() {
            let debounceTimer;
            
            const keywordInput = document.getElementById('filter-keyword');
            const countrySelect = document.getElementById('filter-country');
            
            keywordInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => loadCompanies(), 500);
            });
            
            countrySelect.addEventListener('change', () => loadCompanies());
        }

        // Charger les entreprises depuis l'API
        async function loadCompanies() {
            const tbody = document.getElementById('companies-tbody');
            const noResults = document.getElementById('no-results');
            const tableContainer = document.querySelector('.table-container');
            
            // Afficher le loader
            tbody.innerHTML = `
                <tr>
                    <td colspan="7">
                        <div class="loader">
                            <div class="spinner"></div>
                            <p>Chargement des entreprises...</p>
                        </div>
                    </td>
                </tr>
            `;
            
            try {
                // Récupérer les filtres
                const keyword = document.getElementById('filter-keyword').value;
                const country = document.getElementById('filter-country').value;
                
                // Construire l'URL
                const params = new URLSearchParams();
                if (keyword) params.append('keyword', keyword);
                if (country) params.append('country', country);
                
                const url = `${baseUrl}/function/getCompanies.php?${params.toString()}`;
                
                // Appel API
                const response = await fetch(url);
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.error || 'Erreur lors du chargement');
                }
                
                companies = data.companies;
                
                // Mettre à jour le compteur
                document.getElementById('companies-count').textContent = companies.length;
                
                // Afficher les résultats
                if (companies.length === 0) {
                    tableContainer.style.display = 'none';
                    noResults.style.display = 'block';
                } else {
                    tableContainer.style.display = 'block';
                    noResults.style.display = 'none';
                    renderCompanies();
                }
                
            } catch (error) {
                console.error('Erreur:', error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7">
                            <div class="no-results">
                                <div class="icon">⚠️</div>
                                <h3>Erreur de chargement</h3>
                                <p>${error.message}</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
        }

        // Afficher les entreprises dans le tableau
        function renderCompanies() {
            const tbody = document.getElementById('companies-tbody');
            
            tbody.innerHTML = companies.map(company => `
                <tr>
                    <td>
                        <div class="company-name">${escapeHtml(company.name || 'Sans nom')}</div>
                        ${company.domain ? `<div class="company-domain">${escapeHtml(company.domain)}</div>` : ''}
                    </td>
                    <td>
                        <div class="company-location">
                            ${company.hq_location || company.country ? 
                                `📍 ${escapeHtml(company.hq_location || '')}${company.hq_location && company.country ? ', ' : ''}${escapeHtml(company.country || '')}` 
                                : '<span style="color: #9ca3af; font-style: italic;">Non renseigné</span>'}
                        </div>
                    </td>
                    <td>
                        ${company.capacity ? `<span class="stat-value">${company.capacity}</span>` : '<span style="color: #9ca3af;">-</span>'}
                    </td>
                    <td>
                        <span class="stat-value">${company.jobs_count}</span>
                    </td>
                    <td>
                        <span class="stat-value">${company.accounts_count}</span>
                    </td>
                    <td>
                        ${company.recruiting ? 
                            '<span class="badge badge-success">✓ Recrutement actif</span>' : 
                            '<span class="badge badge-danger">✗ Pas de recrutement</span>'}
                    </td>
                    <td class="actions-cell">
                        <div class="actions-dropdown">
                            <button class="btn-actions" onclick="toggleDropdown(event, ${company.id})">
                                Actions ▼
                            </button>
                            <div class="dropdown-menu" id="dropdown-${company.id}">
                                <button class="dropdown-item" onclick="viewCompany(${company.id})">
                                    👁️ Voir l'entreprise
                                </button>
                                <button class="dropdown-item" onclick="viewCompanyJobs(${company.id})">
                                    📋 Voir les offres (${company.jobs_count})
                                </button>
                                <button class="dropdown-item danger" onclick="deleteCompany(${company.id}, '${escapeHtml(company.name)}')">
                                    🗑️ Supprimer
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Toggle dropdown
        function toggleDropdown(event, companyId) {
            event.stopPropagation();
            
            const dropdown = document.getElementById(`dropdown-${companyId}`);
            
            // Fermer les autres dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu.id !== `dropdown-${companyId}`) {
                    menu.classList.remove('active');
                }
            });
            
            // Toggle le dropdown actuel
            dropdown.classList.toggle('active');
            activeDropdown = dropdown.classList.contains('active') ? dropdown : null;
        }

        // Fermer le dropdown quand on clique ailleurs
        document.addEventListener('click', () => {
            if (activeDropdown) {
                activeDropdown.classList.remove('active');
                activeDropdown = null;
            }
        });

        // Voir l'entreprise
        function viewCompany(companyId) {
            const url = `${baseUrl}/function/getViewerCompany.php?id=${companyId}`;
            loadPopupContent('popup-company', url);
            openPopup('popup-company');
        }

        // Voir les offres d'emploi de l'entreprise
        function viewCompanyJobs(companyId) {
            const url = `${baseUrl}/function/getCompanyJobs.php?id=${companyId}`;
            loadPopupContent('popup-jobs', url);
            openPopup('popup-jobs');
        }

        // Supprimer l'entreprise
        async function deleteCompany(companyId, companyName) {
            if (!confirm(`⚠️ ATTENTION ⚠️\n\nVous êtes sur le point de supprimer l'entreprise "${companyName}".\n\nCette action supprimera également :\n- Toutes les offres d'emploi de l'entreprise\n- Tous les comptes utilisateurs associés\n- Les contacts de l'entreprise\n\nCette action est IRRÉVERSIBLE.\n\nConfirmez-vous la suppression ?`)) {
                return;
            }
            
            try {
                const formData = new FormData();
                formData.append('id', companyId);
                
                const response = await fetch(`${baseUrl}/function/deleteCompany.php`, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification(
                        `Entreprise "${companyName}" supprimée avec succès\n` +
                        `${data.deleted.jobs} offre(s) d'emploi supprimée(s)\n` +
                        `${data.deleted.accounts} compte(s) supprimé(s)`,
                        'success'
                    );
                    loadCompanies(); // Recharger la liste
                } else {
                    throw new Error(data.error || 'Erreur lors de la suppression');
                }
                
            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Erreur lors de la suppression: ' + error.message, 'error');
            }
        }

        // Réinitialiser les filtres
        function resetFilters() {
            document.getElementById('filter-keyword').value = '';
            document.getElementById('filter-country').value = '';
            loadCompanies();
        }

        // Afficher une notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <div class="icon">${type === 'success' ? '✓' : '✗'}</div>
                <div class="message">${escapeHtml(message)}</div>
                <div class="close" onclick="this.parentElement.remove()">✕</div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Échapper le HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
