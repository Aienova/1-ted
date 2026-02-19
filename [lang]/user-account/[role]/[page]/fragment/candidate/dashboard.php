<!-- Section des statistiques -->
<div class="stats-grid">
    <div class="stat-card" id="stat-cv">
        <div class="stat-icon">📄</div>
        <div class="stat-details">
            <div class="stat-value">-</div>
            <div class="stat-label">CV</div>
        </div>
    </div>
    
    <div class="stat-card" id="stat-applications">
        <div class="stat-icon">📋</div>
        <div class="stat-details">
            <div class="stat-value">0</div>
            <div class="stat-label">Candidatures</div>
        </div>
    </div>
    
    <div class="stat-card" id="stat-in-process">
        <div class="stat-icon">👁️</div>
        <div class="stat-details">
            <div class="stat-value">0</div>
            <div class="stat-label">En cours</div>
        </div>
    </div>
    
    <div class="stat-card" id="stat-validated">
        <div class="stat-icon">✅</div>
        <div class="stat-details">
            <div class="stat-value">0</div>
            <div class="stat-label">Sélectionnées</div>
        </div>
    </div>
</div>

<!-- Section des actions rapides -->
<div class="quick-actions">
    <h2 style="margin-bottom: 1.5rem; color: #333;">Actions rapides</h2>
    <div class="dashboard-cards">
        <div class="dashboard-card">
            <h3>📝 Mon CV</h3>
            <p>Créez et mettez à jour votre CV professionnel pour maximiser vos chances.</p>
            <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/candidate/my-cv" class="btn-card" id="btn-create-cv">Créer mon CV</a>
        </div>
        
        <div class="dashboard-card">
            <h3>🔍 Recherche d'offres</h3>
            <p>Explorez les offres d'emploi et trouvez l'opportunité idéale.</p>
            <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/offers" class="btn-card">Rechercher</a>
        </div>
        
        <div class="dashboard-card">
            <h3>👤 Mon profil</h3>
            <p>Gérez vos informations personnelles et vos préférences.</p>
            <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/candidate/my-profile" class="btn-card">Mon profil</a>
        </div>
    </div>
</div>

<!-- Section des candidatures récentes -->
<div class="recent-applications">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0; color: #333;">Mes candidatures récentes</h2>
        <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/candidate/my-applications" style="color: #667eea; text-decoration: none; font-weight: 500;">Voir tout →</a>
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

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.2);
    border-color: #667eea;
}

.stat-icon {
    font-size: 2.5rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.stat-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    grid-template-columns: 2fr 1fr auto;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #eee;
    align-items: center;
}

.application-item-small .status-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.application-item-small .status-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    flex-shrink: 0;
}

.application-item-small .status-icon.pending {
    background: #fff7ed;
}

.application-item-small .status-icon.in-process {
    background: #eff6ff;
}

.application-item-small .status-icon.question {
    background: #f5f3ff;
}

.application-item-small .status-icon.rejected {
    background: #fef2f2;
}

.application-item-small .status-icon.validate {
    background: #f0fdf4;
}

.application-item-small .status-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
}

.application-item-small:last-child {
    border-bottom: none;
}

.application-item-small:hover {
    background-color: #f8f9fa;
}

.application-job-info h4 {
    margin: 0 0 0.25rem 0;
    color: #333;
    font-size: 1rem;
}

.application-job-info p {
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
    loadCandidateStats();
    loadRecentApplications();
});

// Charger les statistiques du candidat
function loadCandidateStats() {
    fetch('<?php echo $baseUrl; ?>/function/getCandidateStats.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Statut du CV
                const cvCard = document.getElementById('stat-cv');
                const cvValue = cvCard.querySelector('.stat-value');
                const btnCreateCV = document.getElementById('btn-create-cv');
                
                if (data.has_cv) {
                    cvValue.textContent = '✓';
                    cvValue.style.color = '#10b981';
                    btnCreateCV.textContent = 'Modifier mon CV';
                } else {
                    cvValue.textContent = '✗';
                    cvValue.style.color = '#ef4444';
                }
                
                // Total candidatures
                document.querySelector('#stat-applications .stat-value').textContent = data.total_applications;
                
                // En cours (in-process + question)
                document.querySelector('#stat-in-process .stat-value').textContent = data.in_process;
                
                // Sélectionnées (validated)
                document.querySelector('#stat-validated .stat-value').textContent = data.validated;
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des statistiques:', error);
        });
}

// Charger les candidatures récentes
function loadRecentApplications() {
    fetch('<?php echo $baseUrl; ?>/function/getMyCandidatures.php?limit=5')
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
        container.innerHTML = '<p style="text-align: center; color: #666; padding: 2rem;">Aucune candidature pour le moment. Commencez par rechercher des offres!</p>';
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
        
        html += `
            <div class="application-item-small">
                <div class="application-job-info">
                    <h4>${app.job_title}</h4>
                    <p>${app.company_name}</p>
                </div>
                <div class="application-date">${date}</div>
                <div class="status-container">
                    <div class="status-icon ${app.status}" style="color: ${status.color}; font-size: 1.5rem;">
                        ${status.icon}
                    </div>
                    <div class="status-label" style="color: ${status.color};">
                        ${status.tooltip}
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}
</script>
