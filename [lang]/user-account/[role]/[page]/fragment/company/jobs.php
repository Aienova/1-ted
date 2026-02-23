<!-- Page de gestion des offres d'emploi de l'entreprise -->

<div class="jobs-management">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="margin: 0; color: #333;">Nos offres d'emploi</h1>
        <button onclick="openAddModal()" class="btn-action btn-primary">
            ➕ Créer une offre
        </button>
    </div>
    
    <!-- Filtres -->
    <div class="filters-container">
        <div class="filter-group">
            <input 
                type="text" 
                id="search-keyword" 
                placeholder="🔍 Rechercher par titre, lieu..." 
                class="filter-input"
            >
        </div>
        <div class="filter-group">
            <select id="filter-status" class="filter-select">
                <option value="all">Tous les statuts</option>
                <option value="open">Ouvertes</option>
                <option value="closed">Fermées</option>
                <option value="approved">Approuvées</option>
                <option value="pending">En attente d'approbation</option>
            </select>
        </div>
    </div>
    
    <!-- Liste des offres -->
    <div id="jobs-list" class="jobs-list">
        <p style="text-align: center; color: #999; padding: 2rem;">Chargement des offres...</p>
    </div>
</div>

<!-- Modal pour éditer une offre -->
<div id="edit-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Modifier l'offre d'emploi</h2>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <div id="modal-body" class="modal-body">
            <p style="text-align: center; padding: 2rem;">Chargement...</p>
        </div>
    </div>
</div>

<style>
.jobs-management {
    max-width: 1200px;
    margin: 0 auto;
}

.btn-action {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #1e93e5 0%, #4b97a2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.filters-container {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 1rem;
}

.filter-input,
.filter-select {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    width: 100%;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.jobs-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.job-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.job-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.job-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.job-title-section h3 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-size: 1.3rem;
}

.job-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    color: #666;
    font-size: 0.9rem;
}

.job-meta span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.job-status-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-open {
    background: #d1fae5;
    color: #065f46;
}

.status-closed {
    background: #fee2e2;
    color: #991b1b;
}

.status-approved {
    background: #dbeafe;
    color: #1e40af;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.job-description {
    color: #555;
    line-height: 1.6;
    margin-bottom: 1rem;
    max-height: 3em;
    overflow: hidden;
    text-overflow: ellipsis;
}

.job-stats {
    display: flex;
    gap: 2rem;
    padding: 1rem 0;
    border-top: 1px solid #eee;
    margin-top: 1rem;
}

.job-stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #666;
}

.job-stat-value {
    font-weight: 600;
    font-size: 1.1rem;
    color: #333;
}

.job-actions {
    display: flex;
    gap: 0.75rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.btn-small {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-edit {
    background: #3b82f6;
    color: white;
}

.btn-edit:hover {
    background: #2563eb;
}

.btn-toggle {
    background: #f59e0b;
    color: white;
}

.btn-toggle:hover {
    background: #d97706;
}

.btn-view {
    background: #8b5cf6;
    color: white;
}

.btn-view:hover {
    background: #7c3aed;
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    overflow: auto;
}

.modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: white;
    border-radius: 12px;
    width: 90%;
    max-width: 1200px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
}

.modal-header h2 {
    margin: 0;
    color: #333;
}

.modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: #666;
    line-height: 1;
    padding: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s;
}

.modal-close:hover {
    background: #f3f4f6;
    color: #333;
}

.modal-body {
    padding: 1.5rem;
    overflow-y: auto;
    flex: 1;
}
</style>

<script>
let allJobs = [];
let searchTimeout;

// Charger les offres au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadJobs();
    
    // Événements des filtres
    document.getElementById('search-keyword').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadJobs();
        }, 500);
    });
    
    document.getElementById('filter-status').addEventListener('change', function() {
        loadJobs();
    });
});

// Charger les offres d'emploi
function loadJobs() {
    const keyword = document.getElementById('search-keyword').value;
    const status = document.getElementById('filter-status').value;
    
    let url = '<?php echo $baseUrl; ?>/function/getMyCompanyJobs.php?';
    if (keyword) url += `keyword=${encodeURIComponent(keyword)}&`;
    if (status !== 'all') url += `status=${status}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allJobs = data.jobs;
                displayJobs(data.jobs);
            } else {
                document.getElementById('jobs-list').innerHTML = 
                    '<p style="text-align: center; color: #ef4444;">Erreur lors du chargement des offres.</p>';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('jobs-list').innerHTML = 
                '<p style="text-align: center; color: #ef4444;">Erreur lors du chargement des offres.</p>';
        });
}

// Afficher les offres
function displayJobs(jobs) {
    const container = document.getElementById('jobs-list');
    
    if (!jobs || jobs.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: #666; padding: 2rem;">Aucune offre d\'emploi. Créez votre première offre!</p>';
        return;
    }
    
    let html = '';
    jobs.forEach(job => {
        const isOpen = job.open == 1;
        const isApproved = job.approved == 1;
        
        html += `
            <div class="job-card">
                <div class="job-header">
                    <div class="job-title-section">
                        <h3>${job.title}</h3>
                        <div class="job-meta">
                            <span>📍 ${job.location || 'Non spécifié'}</span>
                            <span>💼 ${job.contract_type || 'Non spécifié'}</span>
                            ${job.salary ? `<span>💰 ${job.salary}</span>` : ''}
                        </div>
                    </div>
                    <div class="job-status-badges">
                        <span class="status-badge ${isOpen ? 'status-open' : 'status-closed'}">
                            ${isOpen ? '✓ Ouverte' : '✗ Fermée'}
                        </span>
                        <span class="status-badge ${isApproved ? 'status-approved' : 'status-pending'}">
                            ${isApproved ? '✓ Approuvée' : '⏳ En attente'}
                        </span>
                    </div>
                </div>
                
                <div class="job-description">
                    ${job.description ? job.description.substring(0, 200) + '...' : 'Aucune description'}
                </div>
                
                <div class="job-stats">
                    <div class="job-stat">
                        <span>📋 Candidatures :</span>
                        <span class="job-stat-value">${job.total_applications || 0}</span>
                    </div>
                    <div class="job-stat">
                        <span>⏳ En attente :</span>
                        <span class="job-stat-value">${job.pending_applications || 0}</span>
                    </div>
                    <div class="job-stat">
                        <span>📅 Créée le :</span>
                        <span>${new Date(job.created_at).toLocaleDateString('fr-FR')}</span>
                    </div>
                </div>
                
                <div class="job-actions">
                    <button onclick="openEditModal(${job.id})" class="btn-small btn-edit">
                        ✏️ Modifier
                    </button>
                    <button onclick="toggleJobStatus(${job.id}, ${isOpen})" class="btn-small btn-toggle">
                        ${isOpen ? '🔒 Fermer' : '🔓 Ouvrir'}
                    </button>
                    <a href="/<?php echo $lang; ?>/user-account/company/applications?job_id=${job.id}" class="btn-small btn-view">
                        👥 Candidatures (${job.total_applications || 0})
                    </a>
                    <button onclick="deleteJob(${job.id})" class="btn-small btn-delete">
                        🗑️ Supprimer
                    </button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Basculer le statut ouvert/fermé
function toggleJobStatus(jobId, isCurrentlyOpen) {
    const action = isCurrentlyOpen ? 'fermer' : 'ouvrir';
    if (!confirm(`Êtes-vous sûr de vouloir ${action} cette offre?`)) return;
    
    // TODO: Appeler l'API pour mettre à jour le statut
    fetch('<?php echo $baseUrl; ?>/function/updateJobStatus.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            job_id: jobId,
            open: !isCurrentlyOpen
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadJobs(); // Recharger la liste
        } else {
            alert('Erreur: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour');
    });
}

// Supprimer une offre
function deleteJob(jobId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette offre? Cette action est irréversible et supprimera également toutes les candidatures associées.')) return;
    
    // TODO: Appeler l'API pour supprimer
    fetch('<?php echo $baseUrl; ?>/function/deleteJob.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ job_id: jobId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Offre supprimée avec succès');
            loadJobs(); // Recharger la liste
        } else {
            alert('Erreur: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la suppression');
    });
}

// Ouvrir la modal pour ajouter une offre
function openAddModal() {
    const modal = document.getElementById('edit-modal');
    const modalBody = document.getElementById('modal-body');
    const modalTitle = modal.querySelector('.modal-header h2');
    
    // Afficher la modal
    modal.classList.add('active');
    
    // Changer le titre
    modalTitle.textContent = 'Créer une offre d\'emploi';
    
    // Afficher le message de chargement
    modalBody.innerHTML = '<p style="text-align: center; padding: 2rem;">Chargement du formulaire...</p>';
    
    // Charger le formulaire d'ajout
    fetch('<?php echo $baseUrl; ?>/data/jobs/add-job-offer.php')
        .then(response => response.text())
        .then(html => {
            modalBody.innerHTML = html;
            
            // Réinitialiser les scripts si nécessaire
            const scripts = modalBody.querySelectorAll('script');
            scripts.forEach(script => {
                const newScript = document.createElement('script');
                if (script.src) {
                    newScript.src = script.src;
                } else {
                    newScript.textContent = script.textContent;
                }
                script.parentNode.replaceChild(newScript, script);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement:', error);
            modalBody.innerHTML = '<p style="text-align: center; color: #ef4444; padding: 2rem;">Erreur lors du chargement du formulaire.</p>';
        });
}

// Ouvrir la modal d'édition
function openEditModal(jobId) {
    const modal = document.getElementById('edit-modal');
    const modalBody = document.getElementById('modal-body');
    const modalTitle = modal.querySelector('.modal-header h2');
    
    // Afficher la modal
    modal.classList.add('active');
    
    // Changer le titre
    modalTitle.textContent = 'Modifier l\'offre d\'emploi';
    
    // Afficher le message de chargement
    modalBody.innerHTML = '<p style="text-align: center; padding: 2rem;">Chargement de l\'offre...</p>';
    
    // Charger le contenu de l'offre en mode modal
    fetch(`<?php echo $baseUrl; ?>/data/jobs/${jobId}?modal=true`)
        .then(response => response.text())
        .then(html => {
            modalBody.innerHTML = html;
            
            // Réinitialiser les scripts si nécessaire
            const scripts = modalBody.querySelectorAll('script');
            scripts.forEach(script => {
                const newScript = document.createElement('script');
                if (script.src) {
                    newScript.src = script.src;
                } else {
                    newScript.textContent = script.textContent;
                }
                script.parentNode.replaceChild(newScript, script);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement:', error);
            modalBody.innerHTML = '<p style="text-align: center; color: #ef4444; padding: 2rem;">Erreur lors du chargement de l\'offre.</p>';
        });
}

// Fermer la modal d'édition
function closeEditModal() {
    const modal = document.getElementById('edit-modal');
    modal.classList.remove('active');
    
    // Recharger la liste des offres après la fermeture
    setTimeout(() => {
        loadJobs();
    }, 300);
}

// Fermer la modal si on clique en dehors
document.addEventListener('click', function(event) {
    const modal = document.getElementById('edit-modal');
    if (event.target === modal) {
        closeEditModal();
    }
});

// Fermer la modal avec la touche Echap
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('edit-modal');
        if (modal.classList.contains('active')) {
            closeEditModal();
        }
    }
});
</script>
