<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Candidatures</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/account-system.css">
    <style>
        /* Surcharge du style body pour les pages de gestion */
        body {
            background: #f5f7fa !important;
            display: block !important;
            align-items: initial !important;
            justify-content: initial !important;
            min-height: 100vh;
        }
    </style>
</head>
<body>

<div class="applications-container">
    <div class="page-header">
        <h1>📋 Gestion des Candidatures</h1>
        <p>Suivez et gérez toutes les candidatures aux offres d'emploi</p>
    </div>

    <!-- Filtres -->
    <div class="filters-section">
        <div class="filters-grid">
            <div class="filter-group">
                <label for="filter-keyword">🔍 Rechercher</label>
                <input 
                    type="text" 
                    id="filter-keyword" 
                    placeholder="Nom, prénom, poste, société..."
                >
            </div>

            <div class="filter-group">
                <label for="filter-status">📊 Statut</label>
                <select id="filter-status">
                    <option value="all">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="in-process">En cours d'examen</option>
                    <option value="question">Examen / Entretien</option>
                    <option value="rejected">Rejeté</option>
                    <option value="validate">Validé / Embauché</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Liste des candidatures -->
    <div class="applications-list" id="applications-list">
        <div class="loading">
            Chargement des candidatures...
        </div>
    </div>
</div>

<!-- Popup pour les coordonnées -->
<?php
$popupId = 'popup-contact';
$popupSize = 'medium';
$popupClass = '';
include __DIR__ . '/blocks/popup.php';
?>

<!-- Popup pour afficher le CV -->
<?php
$popupId = 'popup-cv';
$popupSize = 'large';
$popupClass = '';
include __DIR__ . '/blocks/popup.php';
?>

<script src="<?php echo $baseUrl; ?>/script/popup.js"></script>
<script>
    let applicationsData = [];
    let debounceTimer;

    // Chargement initial
    document.addEventListener('DOMContentLoaded', function() {
        loadApplications();
        
        // Événements filtres
        document.getElementById('filter-keyword').addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(loadApplications, 500);
        });
        
        document.getElementById('filter-status').addEventListener('change', loadApplications);
    });

    // Charger les candidatures
    function loadApplications() {
        const keyword = document.getElementById('filter-keyword').value;
        const status = document.getElementById('filter-status').value;
        
        const params = new URLSearchParams();
        if (keyword) params.append('keyword', keyword);
        if (status && status !== 'all') params.append('status', status);
        
        fetch(`${baseUrl}/function/getApplications.php?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    applicationsData = data.applications;
                    displayApplications(applicationsData);
                } else {
                    console.error('Erreur:', data.error);
                    showError(data.error);
                }
            })
            .catch(error => {
                console.error('Erreur réseau:', error);
                showError('Erreur de chargement des candidatures');
            });
    }

    // Afficher les candidatures
    function displayApplications(applications) {
        const container = document.getElementById('applications-list');
        
        if (applications.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <h3>Aucune candidature trouvée</h3>
                    <p>Aucune candidature ne correspond à vos critères de recherche.</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = applications.map(app => {
            const statusInfo = getStatusInfo(app.status);
            
            return `
                <div class="application-item">
                    <!-- Candidat -->
                    <div class="application-candidate">
                        <img 
                            src="${baseUrl}/${app.candidate_photo_url}" 
                            alt="${app.first_name} ${app.last_name}"
                            class="candidate-photo"
                            onerror="this.src='/media/image/icon/avatar/default.png'"
                        >
                        <div class="candidate-info">
                            <div class="candidate-name">${app.first_name} ${app.last_name}</div>
                            <div class="candidate-email">${app.candidate_email || 'Email non renseigné'}</div>
                        </div>
                    </div>
                    
                    <!-- Statut -->
                    <div class="application-status">
                        <div class="status-icon ${app.status}">
                            ${statusInfo.icon}
                            <div class="status-tooltip">${statusInfo.label}</div>
                        </div>
                    </div>
                    
                    <!-- Job et société -->
                    <div class="application-job">
                        <div class="job-title">${app.job_title || 'Poste non défini'}</div>
                        <div class="company-name">${app.company_name || 'Société inconnue'}</div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="application-actions">
                        ${app.candidate_cv_url ? `
                            <button class="btn-action btn-view-cv" onclick="viewCV('${app.candidate_cv_url}')">
                                👁️ Voir le CV
                            </button>
                        ` : ''}
                        ${app.job_url ? `
                            <button class="btn-action btn-view-job" onclick="viewJob('${app.job_url}')">
                                📄 Voir l'offre
                            </button>
                        ` : ''}
                        <button class="btn-action btn-contact" onclick="showContactPopup(${app.application_id})">
                            📞 Contact
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Obtenir les infos de statut
    function getStatusInfo(status) {
        const statuses = {
            'pending': {
                icon: '⏳',
                label: 'En attente'
            },
            'in-process': {
                icon: '👁️',
                label: 'En cours d\'examen'
            },
            'question': {
                icon: '❓',
                label: 'Examen / Entretien'
            },
            'rejected': {
                icon: '❌',
                label: 'Rejeté'
            },
            'validate': {
                icon: '✅',
                label: 'Validé / Embauché'
            }
        };
        
        return statuses[status] || { icon: '❔', label: 'Statut inconnu' };
    }

    // Voir le CV
    function viewCV(cvUrl) {
        // Charger le CV dans la popup
        const fullUrl = `${baseUrl}/${cvUrl}`;
        
        // Afficher un loader
        openPopup('popup-cv', '<div class="popup-loader"><div class="spinner"></div><p>Chargement du CV...</p></div>');
        
        // Charger le contenu du CV
        fetch(fullUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors du chargement du CV');
                }
                return response.text();
            })
            .then(html => {
                // Parser le HTML pour retirer les classifications (admin peut tout voir)
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Retirer toutes les classes "classified" pour déclassifier le CV
                const classifiedElements = doc.querySelectorAll('.classified');
                classifiedElements.forEach(element => {
                    element.classList.remove('classified');
                });
                
                // Récupérer le HTML déclassifié
                const declassifiedHtml = doc.body.innerHTML;
                
                // Afficher le CV déclassifié dans la popup
                const popup = document.getElementById('popup-cv');
                const contentContainer = popup.querySelector('.popup-content');
                if (contentContainer) {
                    contentContainer.innerHTML = declassifiedHtml;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                openPopup('popup-cv', `
                    <div class="popup-error">
                        <span class="error-icon">⚠️</span>
                        <h3>Erreur de chargement</h3>
                        <p>${error.message}</p>
                        <button onclick="closePopup('popup-cv')" class="btn-close-error">Fermer</button>
                    </div>
                `);
            });
    }

    // Voir l'offre d'emploi
    function viewJob(jobUrl) {
        window.open(`${baseUrl}/${jobUrl}`, '_blank');
    }

    // Afficher la popup de contact
    function showContactPopup(applicationId) {
        const app = applicationsData.find(a => a.application_id == applicationId);
        
        if (!app) {
            alert('Candidature introuvable');
            return;
        }
        
        const popupContent = `
            <div style="padding: 20px;">
                <h2 style="margin-bottom: 20px; color: #111827; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                    📞 Coordonnées de contact
                </h2>
                
                <!-- Candidat -->
                <div style="margin-bottom: 25px; padding: 15px; background: #f9fafb; border-radius: 8px;">
                    <h3 style="margin-bottom: 12px; color: #374151; font-size: 1.1rem;">
                        👤 Candidat : ${app.first_name} ${app.last_name}
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        ${app.candidate_email ? `
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong style="min-width: 80px;">Email :</strong>
                                <a href="mailto:${app.candidate_email}" style="color: #2563eb; text-decoration: none;">
                                    ${app.candidate_email}
                                </a>
                            </div>
                        ` : '<div style="color: #9ca3af;">Email non renseigné</div>'}
                        
                        ${app.candidate_phone ? `
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong style="min-width: 80px;">Téléphone :</strong>
                                <a href="tel:${app.candidate_phone}" style="color: #2563eb; text-decoration: none;">
                                    ${app.candidate_phone}
                                </a>
                            </div>
                        ` : '<div style="color: #9ca3af;">Téléphone non renseigné</div>'}
                    </div>
                </div>
                
                <!-- Recruteur -->
                <div style="padding: 15px; background: #eff6ff; border-radius: 8px;">
                    <h3 style="margin-bottom: 12px; color: #374151; font-size: 1.1rem;">
                        🏢 Recruteur : ${app.company_name || 'Société inconnue'}
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        ${app.recruiter_email || app.company_email ? `
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong style="min-width: 80px;">Email :</strong>
                                <a href="mailto:${app.recruiter_email || app.company_email}" style="color: #2563eb; text-decoration: none;">
                                    ${app.recruiter_email || app.company_email}
                                </a>
                            </div>
                        ` : '<div style="color: #9ca3af;">Email non renseigné</div>'}
                        
                        ${app.recruiter_phone || app.company_phone ? `
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong style="min-width: 80px;">Téléphone :</strong>
                                <a href="tel:${app.recruiter_phone || app.company_phone}" style="color: #2563eb; text-decoration: none;">
                                    ${app.recruiter_phone || app.company_phone}
                                </a>
                            </div>
                        ` : '<div style="color: #9ca3af;">Téléphone non renseigné</div>'}
                    </div>
                </div>
                
                ${app.comment ? `
                    <div style="margin-top: 20px; padding: 15px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
                        <strong style="color: #92400e;">💬 Commentaire :</strong>
                        <p style="margin-top: 8px; color: #78350f;">${app.comment}</p>
                    </div>
                ` : ''}
            </div>
        `;
        
        openPopup('popup-contact', popupContent);
    }

    // Afficher une erreur
    function showError(message) {
        const container = document.getElementById('applications-list');
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">⚠️</div>
                <h3>Erreur</h3>
                <p>${message}</p>
            </div>
        `;
    }
</script>

</body>
</html>
