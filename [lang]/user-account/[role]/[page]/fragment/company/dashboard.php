<!-- Section des statistiques -->
<div class="stats-grid">
    <div class="stat-card" id="stat-jobs">
        <div class="stat-icon">💼</div>
        <div class="stat-details">
            <div class="stat-value">0</div>
            <div class="stat-label">Offres actives</div>
        </div>
    </div>
    
    <div class="stat-card" id="stat-applications">
        <div class="stat-icon">📋</div>
        <div class="stat-details">
            <div class="stat-value">0</div>
            <div class="stat-label">Candidatures</div>
        </div>
    </div>
    
    <div class="stat-card" id="stat-pending">
        <div class="stat-icon">⏳</div>
        <div class="stat-details">
            <div class="stat-value">0</div>
            <div class="stat-label">En attente</div>
        </div>
    </div>
    
    <div class="stat-card" id="stat-validated">
        <div class="stat-icon">✅</div>
        <div class="stat-details">
            <div class="stat-value">0</div>
            <div class="stat-label">Validées</div>
        </div>
    </div>
</div>

<!-- Section des actions rapides -->
<div class="quick-actions">
    <h2 style="margin-bottom: 1.5rem; color: #333;">Actions rapides</h2>
    <div class="dashboard-cards">
        <div class="dashboard-card">
            <h3>🏢 Profil entreprise</h3>
            <p>Gérez les informations de votre entreprise et votre marque employeur.</p>
            <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/company/profil-entreprise" class="btn-card">Gérer le profil</a>
        </div>
        
        <div class="dashboard-card">
            <h3>💼 Nos offres d'emploi</h3>
            <p>Créez et publiez vos offres d'emploi pour recruter les meilleurs talents.</p>
            <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/company/our-offers" class="btn-card">Gérer les offres</a>
        </div>
        
        <div class="dashboard-card">
            <h3>📋 Candidatures reçues</h3>
            <p>Consultez et gérez toutes les candidatures pour vos offres.</p>
            <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/company/applications" class="btn-card">Voir les candidatures</a>
        </div>
    </div>
</div>

<!-- Section des candidatures récentes -->
<div class="recent-applications">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0; color: #333;">Candidatures récentes</h2>
        <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/company/candidatures" style="color: #667eea; text-decoration: none; font-weight: 500;">Voir tout →</a>
    </div>
    
    <div id="recent-applications-list">
        <p style="text-align: center; color: #999; padding: 2rem;">Chargement...</p>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.quick-actions {
    margin: 2rem 0;
}

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.dashboard-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.dashboard-card h3 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-size: 1.1rem;
}

.dashboard-card p {
    margin: 0 0 1rem 0;
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
}

.btn-card {
    display: inline-block;
    margin-top: 12px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #1e93e5 0%, #4b97a2 100%);
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.recent-applications {
    margin: 2rem 0;
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.application-item-small {
    display: grid;
    grid-template-columns: auto 2fr 1fr auto;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #eee;
    align-items: center;
}

.application-item-small:last-child {
    border-bottom: none;
}

.application-item-small:hover {
    background-color: #f8f9fa;
}

.candidate-photo-small {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
}

.candidate-info h4 {
    margin: 0 0 0.25rem 0;
    color: #333;
    font-size: 1rem;
}

.candidate-info p {
    margin: 0;
    color: #666;
    font-size: 0.85rem;
}

.application-date {
    color: #999;
    font-size: 0.85rem;
}
</style>

<script>
// Charger les statistiques au chargement
document.addEventListener('DOMContentLoaded', function() {
    loadCompanyStats();
    loadRecentApplications();
});

// Charger les statistiques de l'entreprise
function loadCompanyStats() {
    fetch('<?php echo $baseUrl; ?>/function/getCompanyStats.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Offres actives
                document.querySelector('#stat-jobs .stat-value').textContent = data.open_jobs;
                
                // Total candidatures
                document.querySelector('#stat-applications .stat-value').textContent = data.total_applications;
                
                // En attente
                document.querySelector('#stat-pending .stat-value').textContent = data.pending_applications;
                
                // Validées
                document.querySelector('#stat-validated .stat-value').textContent = data.validated_applications;
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des statistiques:', error);
        });
}

// Charger les candidatures récentes
function loadRecentApplications() {
    fetch('<?php echo $baseUrl; ?>/function/getCompanyApplications.php?limit=5')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayRecentApplications(data.applications);
            } else {
                document.getElementById('recent-applications-list').innerHTML = 
                    '<p style="text-align: center; color: #666;">Aucune candidature pour le moment.</p>';
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des candidatures:', error);
            document.getElementById('recent-applications-list').innerHTML = 
                '<p style="text-align: center; color: #ef4444;">Erreur lors du chargement des candidatures.</p>';
        });
}

// Afficher les candidatures récentes
function displayRecentApplications(applications) {
    const container = document.getElementById('recent-applications-list');
    
    if (!applications || applications.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: #666; padding: 2rem;">Aucune candidature reçue pour le moment.</p>';
        return;
    }
    
    const statusIcons = {
        'pending': { icon: '⏳', color: '#f59e0b', tooltip: 'En attente' },
        'in-process': { icon: '👁️', color: '#3b82f6', tooltip: 'En cours d\'examen' },
        'question': { icon: '❓', color: '#8b5cf6', tooltip: 'Entretien prévu' },
        'rejected': { icon: '❌', color: '#ef4444', tooltip: 'Refusée' },
        'validate': { icon: '✅', color: '#10b981', tooltip: 'Acceptée' }
    };
    
    let html = '';
    applications.forEach(app => {
        const status = statusIcons[app.status] || statusIcons['pending'];
        const date = new Date(app.created_at).toLocaleDateString('fr-FR');
        const photoUrl = app.candidate_photo_url || '';
        
        html += `
            <div class="application-item-small">
                <img src="<?php echo $baseUrl; ?>${photoUrl}" alt="Photo" class="candidate-photo-small" onerror="this.src='<?php echo $baseUrl; ?>/media/image/default-avatar.jpg'">
                <div class="candidate-info">
                    <h4>${app.first_name} ${app.last_name}</h4>
                    <p>${app.job_title}</p>
                </div>
                <div class="application-date">${date}</div>
                <div class="status-icon" style="color: ${status.color}; font-size: 1.5rem;" title="${status.tooltip}">
                    ${status.icon}
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}
</script>
