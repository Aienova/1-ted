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

.btn-priority {
    background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
    color: white;
}

.btn-priority:hover {
    background: linear-gradient(135deg, #db2777 0%, #7c3aed 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(139, 92, 246, 0.3);
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

<!-- Script pour la gestion des priorités -->
<script src="<?php echo $baseUrl; ?>/script/forms/job-priority.js"></script>

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
    
    // Trier les offres par priorité (décroissant), puis par date de création
    jobs.sort((a, b) => {
        const priorityA = parseInt(a.priority) || 0;
        const priorityB = parseInt(b.priority) || 0;
        
        if (priorityB !== priorityA) {
            return priorityB - priorityA; // Priorité décroissante (3, 2, 1, 0)
        }
        
        // Si même priorité, trier par date de création (plus récent d'abord)
        return new Date(b.created_at) - new Date(a.created_at);
    });
    
    let html = '';
    jobs.forEach(job => {
        const isOpen = job.open == 1;
        const isApproved = job.approved == 1;
        
        html += `
            <div class="job-card" data-priority="${job.priority || 0}">
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
                        ${job.priority > 0 ? getJobPriorityBadge(job.priority) : ''}
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
                    <button onclick="showJobPriorityPopup(${job.id}, ${job.priority || 0})" class="btn-small btn-priority">
                        🎯 Priorité
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
            
            // Charger les IDs de la company
            loadCompanyIds().then(() => {
                // Attacher les event listeners aux boutons
                initializeJobFormButtons();
            }).catch(error => {
                console.error('Erreur chargement company IDs:', error);
                alert('❌ Erreur: ' + error.message);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement:', error);
            modalBody.innerHTML = '<p style="text-align: center; color: #ef4444; padding: 2rem;">Erreur lors du chargement du formulaire.</p>';
        });
}

// Charger les IDs de la company connectée
async function loadCompanyIds() {
    console.log('🔍 Chargement des IDs de la company...');
    
    const response = await fetch('<?php echo $baseUrl; ?>/function/getMyCompanyIds.php');
    const data = await response.json();
    
    console.log('📦 Réponse getMyCompanyIds:', data);
    
    if (!data.success) {
        throw new Error(data.error || 'Impossible de récupérer les informations de l\'entreprise');
    }
    
    // Stocker les IDs globalement
    window.currentCompanyId = data.company_id;
    window.currentContactId = data.contact_id;
    window.currentCompanyName = data.company_name;
    
    // Mettre à jour l'affichage du nom de l'entreprise
    const companyNameDisplay = document.getElementById('company-name-display');
    if (companyNameDisplay) {
        companyNameDisplay.textContent = data.company_name;
    }
    
    const companyNameInfo = document.getElementById('company-name-info');
    if (companyNameInfo) {
        companyNameInfo.textContent = data.company_name;
    }
    
    console.log('✅ IDs chargés:', {
        companyId: window.currentCompanyId,
        contactId: window.currentContactId,
        companyName: window.currentCompanyName
    });
}

// Initialiser les event listeners pour les boutons du formulaire d'offre
function initializeJobFormButtons() {
    console.log('🔧 Initialisation des boutons du formulaire...');
    
    // Bouton "Ajouter l'offre"
    const addButton = document.getElementById('add-job-button');
    if (addButton) {
        addButton.addEventListener('click', async () => {
            console.log('🆕 Ajout d\'une nouvelle offre d\'emploi...');
            
            try {
                // Vérifier que la company est définie
                if (!window.currentCompanyId) {
                    alert('❌ Erreur: Aucune entreprise associée à votre compte.\n\nVeuillez d\'abord créer votre profil d\'entreprise dans les paramètres.');
                    throw new Error('Aucune entreprise associée à votre compte');
                }
                
                // Collecter les données du formulaire
                const dataByTable = collectDataByTable();
                
                console.log('📋 Données collectées:', dataByTable);
                
                // Insérer l'offre avec la company connectée
                await insertNewJobForLoggedCompany(dataByTable);
                
                alert('✅ Offre d\'emploi créée avec succès!');
                
                // Fermer la modal et recharger la liste
                closeEditModal();
            } catch (error) {
                console.error('❌ Erreur lors de l\'ajout:', error);
                if (error.message !== 'Aucune entreprise associée à votre compte') {
                    alert('❌ Erreur lors de l\'ajout de l\'offre: ' + error.message);
                }
            }
        });
    }
    
    // Bouton "Données test"
    const testButton = document.getElementById('test-job-button');
    if (testButton) {
        testButton.addEventListener('click', () => {
            console.log('🧪 Insertion des données de test...');
            insertTestDataJobSimplified();
        });
    }
    
    // Bouton "Réinitialiser"
    const resetButton = document.getElementById('reset-job-button');
    if (resetButton) {
        resetButton.addEventListener('click', () => {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ?')) {
                openAddModal(); // Recharger le formulaire
            }
        });
    }
    
    // Bouton "Imprimer"
    const printButton = document.getElementById('print-job-button');
    if (printButton) {
        printButton.addEventListener('click', () => {
            window.print();
        });
    }
    
    console.log('✅ Boutons initialisés:', {
        addButton: !!addButton,
        testButton: !!testButton,
        resetButton: !!resetButton,
        printButton: !!printButton
    });
}

// Fonction simplifiée pour insérer une offre avec la company déjà connectée
async function insertNewJobForLoggedCompany(dataByTable) {
    console.log('📦 Insertion offre pour company ID:', window.currentCompanyId);
    
    const insertedIds = {
        company_id: window.currentCompanyId,
        contact_id: window.currentContactId
    };
    
    // ÉTAPE 1: Insérer requirement (si présent)
    if (dataByTable.requirement) {
        console.log('1️⃣ Insertion requirement...');
        
        const requirementData = {};
        for (const [column, value] of Object.entries(dataByTable.requirement)) {
            if (value !== null && value !== undefined && value !== '' && value !== '--' && column !== 'id') {
                requirementData[column] = value;
            }
        }
        
        const requirementResponse = await fetch('<?php echo $baseUrl; ?>/function/insertData.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                table: 'requirement',
                data: requirementData
            })
        });
        
        const requirementResult = await requirementResponse.json();
        console.log('✅ Résultat requirement:', requirementResult);
        
        if (!requirementResult.success) {
            throw new Error('Échec insertion requirement: ' + (requirementResult.error || requirementResult.message));
        }
        
        insertedIds.requirement_id = requirementResult.id;
    }
    
    // ÉTAPE 2: Insérer job avec tous les IDs
    console.log('2️⃣ Insertion job...');
    
    const jobData = {
        company_id: insertedIds.company_id,
        contact_id: insertedIds.contact_id,
        requirement_id: insertedIds.requirement_id || null
    };
    
    // Ajouter les données de la table job
    if (dataByTable.job) {
        for (const [column, value] of Object.entries(dataByTable.job)) {
            if (column !== 'id') {
                // Traiter spécialement need_vehicle pour le convertir en entier
                if (column === 'need_vehicle') {
                    jobData[column] = value ? 1 : 0;
                } else if (value !== null && value !== undefined && value !== '' && value !== '--') {
                    jobData[column] = value;
                }
            }
        }
    }
    
    // Par défaut, l'offre est ouverte
    jobData.open = 1;
    
    console.log('📤 Données job:', jobData);
    const jobResponse = await fetch('<?php echo $baseUrl; ?>/function/insertData.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            table: 'job',
            data: jobData
        })
    });
    
    const jobResult = await jobResponse.json();
    console.log('✅ Résultat job:', jobResult);
    
    if (!jobResult.success) {
        throw new Error('Échec insertion job: ' + (jobResult.error || jobResult.message));
    }
    
    const jobId = jobResult.id;
    console.log('🎉 Offre d\'emploi créée avec succès! ID:', jobId);
    
    // Convertir l'offre en HTML statique
    try {
        await fetch('<?php echo $baseUrl; ?>/function/saveHTMLJob.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ job_id: jobId })
        });
        console.log('✅ HTML généré');
    } catch (error) {
        console.warn('⚠️ Erreur génération HTML:', error);
    }
    
    return jobId;
}

// Insérer des données de test simplifiées (sans company et contact)
function insertTestDataJobSimplified() {
    const testData = {
        job: {
            title: 'Développeur Full Stack Senior',
            location: 'Paris, France',
            salary: '50000 - 65000 € par an',
            contract_type: 'CDI',
            start_date: '2024-03-01',
            length: 'Temps plein',
            need_vehicle: '0',
            description: 'Nous recherchons un développeur Full Stack expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants utilisant les dernières technologies web.\\n\\nResponsabilités principales :\\n- Développer et maintenir des applications web complexes\\n- Collaborer avec l\'équipe produit et design\\n- Participer aux revues de code\\n- Mentorer les développeurs juniors',
            work_condition: 'Conditions de travail :\\n- Télétravail partiel (2-3 jours par semaine)\\n- Horaires flexibles\\n- Environnement de travail moderne\\n- Équipe jeune et dynamique\\n- Opportunités de formation continue\\n- Avantages sociaux : tickets restaurant, mutuelle, RTT'
        },
        requirement: {
            degree: 'Bac +5 (Master, diplôme d\'ingénieur)',
            experience_years: '5',
            description: 'Compétences techniques requises :\\n- JavaScript, TypeScript, Node.js\\n- React, Vue.js ou Angular\\n- SQL et NoSQL\\n- Git, Docker\\n- API REST et GraphQL\\n\\nCompétences souhaitées :\\n- Expérience avec des méthodologies Agile\\n- Bonnes capacités de communication\\n- Autonomie et esprit d\'équipe\\n- Anglais professionnel'
        }
    };

    // Insérer les données dans les champs du formulaire
    Object.keys(testData).forEach(table => {
        Object.keys(testData[table]).forEach(column => {
            const element = document.querySelector(
                `.dataToEdit[data-table="${table}"][data-column="${column}"]`
            );
            
            if (element) {
                const value = testData[table][column];
                
                // Mettre à jour l'attribut data-value
                element.setAttribute('data-value', value);
                
                // Mettre à jour le contenu visible
                if (column === 'description' || column === 'work_condition') {
                    // Pour les textes longs, remplacer les retours à la ligne par des <br>
                    element.innerHTML = value.replace(/\\n/g, '<br>');
                } else if (column === 'need_vehicle') {
                    // Pour le booléen véhicule
                    element.textContent = value === '1' ? 'Oui' : 'Non';
                } else {
                    // Pour les autres champs
                    element.textContent = value;
                }
                
                // Marquer le champ comme modifié
                element.classList.add('modified');
            }
        });
    });

    console.log('✅ Données de test insérées');
    alert('Les données de test ont été chargées dans le formulaire.');
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
    
    // Charger le contenu de l'offre en mode modal avec context=user-account pour afficher le validation-notice
    fetch(`<?php echo $baseUrl; ?>/data/jobs/${jobId}?modal=true&context=user-account`)
        .then(response => response.text())
        .then(html => {
            modalBody.innerHTML = html;
            
            // Initialiser les boutons après le chargement
            initializeEditJobFormButtons();
        })
        .catch(error => {
            console.error('Erreur lors du chargement:', error);
            modalBody.innerHTML = '<p style="text-align: center; color: #ef4444; padding: 2rem;">Erreur lors du chargement de l\'offre.</p>';
        });
}

// Initialiser les event listeners pour les boutons du formulaire d'édition
function initializeEditJobFormButtons() {
    console.log('🔧 Initialisation des boutons d\'édition...');
    
    // Bouton "Sauvegarder l'offre"
    const saveButton = document.getElementById('save-job-button');
    if (saveButton) {
        saveButton.addEventListener('click', async () => {
            console.log('💾 Sauvegarde de l\'offre d\'emploi...');
            
            try {
                // Récupérer le conteneur principal
                const jobContainer = document.querySelector('.job-offer-container');
                if (!jobContainer) {
                    alert('❌ Erreur: Conteneur offre d\'emploi introuvable');
                    return;
                }
                
                const jobId = jobContainer.getAttribute('data-id');
                const mode = jobContainer.getAttribute('data-mode') || 'update';
                
                // Collecter toutes les données
                const dataByTable = collectJobDataByTable();
                
                console.log('=== DEBUT SAUVEGARDE OFFRE ===');
                console.log('Données collectées:', dataByTable);
                console.log('Mode:', mode, 'Job ID:', jobId);
                
                // Sauvegarder les données selon le mode
                if (mode === 'update') {
                    console.log('MODE UPDATE');
                    
                    // Utiliser la fonction existante de save-job-offer.js
                    await updateAllJobData(dataByTable, jobId);
                    
                    // Convertir l'offre en HTML statique
                    try {
                        await fetch('<?php echo $baseUrl; ?>/function/saveHTMLJob.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ job_id: jobId })
                        });
                        console.log('✅ HTML généré');
                    } catch (error) {
                        console.warn('⚠️ Erreur génération HTML:', error);
                    }
                    
                    console.log('=== FIN SAUVEGARDE OFFRE ===');
                    
                    alert('✅ Offre d\'emploi sauvegardée avec succès !');
                    
                    // Fermer la modal et recharger la liste
                    closeEditModal();
                } else {
                    throw new Error('Mode non supporté: ' + mode);
                }
                
            } catch (error) {
                console.error('❌ Erreur lors de la sauvegarde:', error);
                alert('❌ Erreur lors de la sauvegarde de l\'offre d\'emploi: ' + error.message);
            }
        });
    }
    
    // Bouton "Réinitialiser"
    const resetButton = document.getElementById('reset-job-button');
    if (resetButton) {
        resetButton.addEventListener('click', () => {
            if (confirm('⚠️ Êtes-vous sûr de vouloir annuler les modifications ? Les changements non sauvegardés seront perdus.')) {
                // Récupérer le job ID et recharger
                const jobContainer = document.querySelector('.job-offer-container');
                if (jobContainer) {
                    const jobId = jobContainer.getAttribute('data-id');
                    if (jobId) {
                        openEditModal(jobId); // Recharger l'offre
                    }
                }
            }
        });
    }
    
    // Bouton "Imprimer"
    const printButton = document.getElementById('print-job-button');
    if (printButton) {
        printButton.addEventListener('click', () => {
            window.print();
        });
    }
    
    console.log('✅ Boutons d\'édition initialisés:', {
        saveButton: !!saveButton,
        resetButton: !!resetButton,
        printButton: !!printButton
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
