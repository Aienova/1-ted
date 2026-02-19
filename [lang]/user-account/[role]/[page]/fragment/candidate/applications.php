<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Candidatures</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/account-system.css">
    <style>
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
        <h1>📋 Mes Candidatures</h1>
        <p>Suivez l'état de vos candidatures et consultez les réponses des recruteurs</p>
    </div>

    <!-- Filtres -->
    <div class="filters-section">
        <div class="filters-grid">
            <div class="filter-group">
                <label for="filter-keyword">🔍 Rechercher</label>
                <input 
                    type="text" 
                    id="filter-keyword" 
                    placeholder="Poste, société..."
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

<!-- Popup pour voir l'offre -->
<?php
$popupId = 'popup-job';
$popupSize = 'large';
$popupClass = '';
include __DIR__ . '/blocks/popup.php';
?>

<!-- Popup pour les coordonnées recruteur -->
<?php
$popupId = 'popup-recruiter';
$popupSize = 'medium';
$popupClass = '';
include __DIR__ . '/blocks/popup.php';
?>

<script src="<?php echo $baseUrl; ?>/script/popup.js"></script>
<script>
    const baseUrl = '<?php echo $baseUrl; ?>';
    
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

    // Charger les candidatures du candidat
    function loadApplications() {
        const keyword = document.getElementById('filter-keyword').value;
        const status = document.getElementById('filter-status').value;
        
        const params = new URLSearchParams();
        if (keyword) params.append('keyword', keyword);
        if (status && status !== 'all') params.append('status', status);
        
        fetch(`${baseUrl}/function/getMyCandidatures.php?${params.toString()}`)
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
                    <p>Vous n'avez pas encore postulé à des offres d'emploi.</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = applications.map(app => {
            const statusInfo = getStatusInfo(app.status);
            
            return `
                <div class="application-item">
                    <!-- Offre et société -->
                    <div class="application-job" style="grid-column: span 2;">
                        <div class="job-title">${app.job_title || 'Poste non défini'}</div>
                        <div class="company-name">${app.company_name || 'Société inconnue'}</div>
                        <div style="font-size: 0.85rem; color: #9ca3af; margin-top: 5px;">
                            Candidature envoyée le ${formatDate(app.created_at)}
                        </div>
                    </div>
                    
                    <!-- Statut -->
                    <div class="application-status">
                        <div class="status-icon ${app.status}">
                            ${statusInfo.icon}
                        </div>
                        <div class="status-label ${app.status}">
                            ${statusInfo.label}
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="application-actions">
                        <button class="btn-action btn-view-job" onclick="viewJobOffer(${app.job_id})">
                            📄 Voir l'offre
                        </button>
                        ${app.status === 'validate' || app.status === 'question' ? `
                            <button class="btn-action btn-contact" onclick="showRecruiterContact(${app.application_id})">
                                📞 Contact recruteur
                            </button>
                        ` : ''}
                        ${app.comment ? `
                            <button class="btn-action" onclick="showComment('${escapeHtml(app.comment)}')" style="background: #fef3c7; color: #92400e;">
                                💬 Voir commentaire
                            </button>
                        ` : ''}
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

    // Voir l'offre d'emploi
    function viewJobOffer(jobId) {
        openPopup('popup-job', '<div class="popup-loader"><div class="spinner"></div><p>Chargement...</p></div>');
        
        fetch(`${baseUrl}/function/getJobDetails.php?id=${jobId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const job = data.job;
                    const content = `
                        <div style="padding: 20px;">
                            <h2 style="color: #111827; margin-bottom: 20px;">${job.title}</h2>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">
                                <div>
                                    <strong>🏢 Société:</strong> ${job.company_name}
                                </div>
                                <div>
                                    <strong>📍 Localisation:</strong> ${job.location || 'Non spécifiée'}
                                </div>
                                <div>
                                    <strong>💰 Salaire:</strong> ${job.salary || 'Non spécifié'}
                                </div>
                                <div>
                                    <strong>📝 Type de contrat:</strong> ${job.contract_type || 'Non spécifié'}
                                </div>
                            </div>
                            <h3 style="margin-top: 20px; margin-bottom: 10px;">Description du poste</h3>
                            <div style="color: #374151; line-height: 1.6; white-space: pre-line;">
                                ${job.description || 'Pas de description'}
                            </div>
                            ${job.work_condition ? `
                                <h3 style="margin-top: 20px; margin-bottom: 10px;">Conditions de travail</h3>
                                <div style="color: #374151; line-height: 1.6; white-space: pre-line;">
                                    ${job.work_condition}
                                </div>
                            ` : ''}
                        </div>
                    `;
                    openPopup('popup-job', content);
                } else {
                    throw new Error(data.error);
                }
            })
            .catch(error => {
                openPopup('popup-job', `
                    <div class="popup-error">
                        <span class="error-icon">⚠️</span>
                        <h3>Erreur</h3>
                        <p>${error.message}</p>
                        <button onclick="closePopup('popup-job')" class="btn-close-error">Fermer</button>
                    </div>
                `);
            });
    }

    // Afficher les coordonnées du recruteur (seulement si sélectionné)
    function showRecruiterContact(applicationId) {
        const app = applicationsData.find(a => a.application_id == applicationId);
        
        if (!app) {
            alert('Candidature introuvable');
            return;
        }
        
        // Vérifier si le candidat peut voir les infos (statut validate ou question)
        if (app.status !== 'validate' && app.status !== 'question') {
            alert('Les coordonnées du recruteur ne sont disponibles que si votre candidature a été sélectionnée.');
            return;
        }
        
        const content = `
            <div style="padding: 20px;">
                <h2 style="margin-bottom: 20px; color: #111827;">📞 Coordonnées du recruteur</h2>
                
                <div style="padding: 15px; background: #eff6ff; border-radius: 8px;">
                    <h3 style="margin-bottom: 12px; color: #374151; font-size: 1.1rem;">
                        🏢 ${app.company_name}
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        ${app.recruiter_email || app.company_email ? `
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong>Email:</strong>
                                <a href="mailto:${app.recruiter_email || app.company_email}" style="color: #2563eb; text-decoration: none;">
                                    ${app.recruiter_email || app.company_email}
                                </a>
                            </div>
                        ` : '<div style="color: #9ca3af;">Email non renseigné</div>'}
                        
                        ${app.company_phone ? `
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong>Téléphone:</strong>
                                <a href="tel:${app.company_phone}" style="color: #2563eb; text-decoration: none;">
                                    ${app.company_phone}
                                </a>
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                <div style="margin-top: 15px; padding: 15px; background: #f0fdf4; border-radius: 8px; border-left: 4px solid #16a34a;">
                    <p style="color: #15803d; margin: 0;">
                        ✅ Félicitations ! Votre candidature a été retenue. N'hésitez pas à contacter le recruteur.
                    </p>
                </div>
            </div>
        `;
        
        openPopup('popup-recruiter', content);
    }

    // Afficher le commentaire
    function showComment(comment) {
        alert('Commentaire du recruteur :\n\n' + comment);
    }

    // Utilitaires
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', { 
            day: '2-digit', 
            month: 'long', 
            year: 'numeric' 
        });
    }

    function escapeHtml(text) {
        return text.replace(/[&<>"']/g, function(m) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[m];
        });
    }

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
