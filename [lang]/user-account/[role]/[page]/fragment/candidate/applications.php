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

<!-- Popup pour le signalement d'entreprise -->
<?php
$popupId = 'popup-redflag';
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
                    // Vérifier le statut d'approbation du profil
                    if (data.approval_status === 0) {
                        // Profil refusé
                        showRejectedProfile();
                    } else if (data.approval_status === null) {
                        // Profil en attente d'approbation
                        showPendingProfile();
                    } else if (data.approval_status === 1) {
                        // Profil approuvé, afficher les candidatures
                        applicationsData = data.applications;
                        displayApplications(applicationsData);
                    } else {
                        // Statut inconnu
                        showError('Statut de profil inconnu');
                    }
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

    // Afficher le message de profil refusé
    function showRejectedProfile() {
        // Masquer les filtres car inutiles
        document.querySelector('.filters-section').style.display = 'none';
        
        const container = document.getElementById('applications-list');
        container.innerHTML = `
            <div style="padding: 30px; background: #fee2e2; border: 2px solid #dc2626; border-radius: 12px; margin: 20px 0;">
                <div style="display: flex; align-items: start; gap: 20px;">
                    <div style="font-size: 3rem; flex-shrink: 0;">❌</div>
                    <div style="flex: 1;">
                        <h2 style="color: #991b1b; margin: 0 0 15px 0; font-size: 1.5rem;">
                            Votre profil a été refusé
                        </h2>
                        <p style="color: #7f1d1d; margin: 0 0 15px 0; line-height: 1.6; font-size: 1rem;">
                            Malheureusement, votre profil candidat n'a pas été approuvé par notre équipe de modération. 
                            Vous ne pouvez pas consulter vos candidatures pour le moment.
                        </p>
                        <p style="color: #7f1d1d; margin: 0 0 15px 0; line-height: 1.6; font-size: 1rem;">
                            <strong>Raisons possibles :</strong>
                        </p>
                        <ul style="color: #7f1d1d; margin: 0 0 15px 0; padding-left: 25px; line-height: 1.8;">
                            <li>Informations incomplètes ou incorrectes</li>
                            <li>Documents non conformes</li>
                            <li>Non-respect des conditions d'utilisation</li>
                            <li>Données frauduleuses ou trompeuses</li>
                        </ul>
                        <p style="color: #7f1d1d; margin: 0; line-height: 1.6; font-size: 1rem;">
                            <strong>📧 Pour plus d'informations :</strong> Contactez notre équipe à 
                            <a href="mailto:support@1-ted.com" style="color: #991b1b; font-weight: bold; text-decoration: underline;">support@1-ted.com</a>
                        </p>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Afficher le message de profil en attente
    function showPendingProfile() {
        // Masquer les filtres car inutiles
        document.querySelector('.filters-section').style.display = 'none';
        
        const container = document.getElementById('applications-list');
        container.innerHTML = `
            <div style="padding: 30px; background: #fef3c7; border: 2px solid #f59e0b; border-radius: 12px; margin: 20px 0;">
                <div style="display: flex; align-items: start; gap: 20px;">
                    <div style="font-size: 3rem; flex-shrink: 0;">⏳</div>
                    <div style="flex: 1;">
                        <h2 style="color: #92400e; margin: 0 0 15px 0; font-size: 1.5rem;">
                            Votre profil n'a pas encore été approuvé
                        </h2>
                        <p style="color: #78350f; margin: 0 0 15px 0; line-height: 1.6; font-size: 1rem;">
                            Votre profil candidat est actuellement en cours de vérification par notre équipe de modération.
                            Vous pourrez consulter et postuler aux offres d'emploi dès que votre profil sera approuvé.
                        </p>
                        <p style="color: #78350f; margin: 0 0 15px 0; line-height: 1.6; font-size: 1rem;">
                            <strong>⌛ Délai habituel :</strong> 24 à 48 heures ouvrées
                        </p>
                        <div style="padding: 15px; background: #fffbeb; border-radius: 8px; border-left: 4px solid #f59e0b;">
                            <p style="color: #78350f; margin: 0; line-height: 1.6; font-size: 0.95rem;">
                                <strong>🔔 Conseil :</strong> En attendant l'approbation, vous pouvez compléter votre profil et ajouter vos compétences 
                                pour maximiser vos chances d'être sélectionné par les recruteurs.
                            </p>
                        </div>
                        <p style="color: #78350f; margin: 15px 0 0 0; line-height: 1.6; font-size: 1rem;">
                            <strong>📧 Questions :</strong> Contactez-nous à 
                            <a href="mailto:support@1-ted.com" style="color: #92400e; font-weight: bold; text-decoration: underline;">support@1-ted.com</a>
                        </p>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Afficher les candidatures
    function displayApplications(applications) {
        // Afficher les filtres car le profil est approuvé
        document.querySelector('.filters-section').style.display = 'block';
        
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
                        ${app.status === 'question' ? `
                            <button class="btn-action btn-contact" onclick="showRecruiterContact(${app.application_id})">
                                📞 Contact recruteur
                            </button>
                        ` : ''}
                        ${app.comment ? `
                            <button class="btn-action" onclick="showComment('${escapeHtml(app.comment)}'" style="background: #fef3c7; color: #92400e;">
                                💬 Voir commentaire
                            </button>
                        ` : ''}
                        <button class="btn-action btn-redflag" onclick="openRedFlagForm(${app.application_id})" style="background: #dc2626; color: white;">
                            🚩 Signaler
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

    // Afficher les coordonnées du recruteur (seulement si status question)
    function showRecruiterContact(applicationId) {
        const app = applicationsData.find(a => a.application_id == applicationId);
        
        if (!app) {
            alert('Candidature introuvable');
            return;
        }
        
        // Vérifier si le candidat peut voir les infos (statut question uniquement)
        if (app.status !== 'question') {
            alert('Les coordonnées du recruteur ne sont disponibles que si votre candidature est en phase d\'entretien.');
            return;
        }
        
        const content = `
            <div style="padding: 20px;">
                <h2 style="margin-bottom: 20px; color: #111827;">📞 Coordonnées du recruteur</h2>
                
                <div style="padding: 15px; background: #eff6ff; border-radius: 8px;">
                    <h3 style="margin-bottom: 12px; color: #374151; font-size: 1.1rem;">
                        🏢 ${app.company_name}
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        ${app.contact_firstname || app.contact_lastname ? `
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong>👤 Contact:</strong>
                                <span>${app.contact_firstname || ''} ${app.contact_lastname || ''}</span>
                            </div>
                        ` : ''}
                        
                        ${app.recruiter_email || app.company_email ? `
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong>📧 Email:</strong>
                                <a href="mailto:${app.recruiter_email || app.company_email}" style="color: #2563eb; text-decoration: none;">
                                    ${app.recruiter_email || app.company_email}
                                </a>
                            </div>
                        ` : '<div style="color: #9ca3af;">📧 Email non renseigné</div>'}
                        
                        ${app.company_phone ? `
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong>📱 Téléphone:</strong>
                                <a href="tel:${app.company_phone}" style="color: #2563eb; text-decoration: none;">
                                    ${app.company_phone}
                                </a>
                            </div>
                        ` : '<div style="color: #9ca3af;">📱 Téléphone non renseigné</div>'}
                    </div>
                </div>
                
                <div style="margin-top: 15px; padding: 15px; background: #f0fdf4; border-radius: 8px; border-left: 4px solid #16a34a;">
                    <p style="color: #15803d; margin: 0;">
                        ✅ Félicitations ! Vous avez été sélectionné pour un entretien. N'hésitez pas à contacter le recruteur.
                    </p>
                </div>
            </div>
        `;
        
        openPopup('popup-recruiter', content);
    }
    
    // Ouvrir le formulaire de signalement
    function openRedFlagForm(applicationId) {
        const app = applicationsData.find(a => a.application_id == applicationId);
        
        if (!app) {
            alert('Candidature introuvable');
            return;
        }
        
        const content = `
            <div style="padding: 20px;">
                <h2 style="margin-bottom: 20px; color: #dc2626;">🚩 Signaler une entreprise</h2>
                
                <div style="padding: 15px; background: #fee2e2; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #dc2626;">
                    <p style="color: #991b1b; margin: 0; font-size: 0.95rem;">
                        <strong>⚠️ Attention:</strong> Cette action est réservée aux cas graves (harcèlement, discrimination, fraude, etc.). 
                        Un signalement abusif peut entraîner des conséquences sur votre compte.
                    </p>
                </div>
                
                <div style="margin-bottom: 20px; padding: 15px; background: #f9fafb; border-radius: 8px;">
                    <h3 style="margin: 0 0 8px 0; color: #374151; font-size: 1rem;">Entreprise concernée</h3>
                    <p style="margin: 0; color: #6b7280;"><strong>${app.company_name}</strong></p>
                    <p style="margin: 5px 0 0 0; color: #9ca3af; font-size: 0.9rem;">Offre: ${app.job_title}</p>
                </div>
                
                <form id="redflag-form" onsubmit="submitRedFlag(event, ${applicationId})">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 500;">
                            Motif du signalement <span style="color: #dc2626;">*</span>
                        </label>
                        <select id="redflag-subject" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                            <option value="">Sélectionnez un motif...</option>
                            <option value="Harcèlement">Harcèlement</option>
                            <option value="Racisme">Racisme / Discrimination</option>
                            <option value="Fraude">Fraude / Arnaque</option>
                            <option value="Menace/Agression">Menace/Agression</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 500;">
                            Description détaillée <span style="color: #dc2626;">*</span>
                        </label>
                        <textarea 
                            id="redflag-reason" 
                            required 
                            rows="6" 
                            placeholder="Décrivez précisément les faits, les circonstances et tout élément pertinent..."
                            style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem; resize: vertical;"
                            oninput="updateCharacterCount()"
                        ></textarea>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                            <p id="char-count" style="margin: 0; color: #6b7280; font-size: 0.85rem;">0 caractère</p>
                            <p id="char-validation" style="margin: 0; font-size: 0.85rem; font-weight: 500;"></p>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" onclick="closePopup('popup-redflag')" style="padding: 10px 20px; background: #e5e7eb; color: #374151; border: none; border-radius: 6px; cursor: pointer; font-size: 1rem;">
                            Annuler
                        </button>
                        <button type="submit" style="padding: 10px 20px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 1rem; font-weight: 500;">
                            🚩 Envoyer le signalement
                        </button>
                    </div>
                </form>
            </div>
        `;
        
        openPopup('popup-redflag', content);
        
        // Initialiser le compteur de caractères
        updateCharacterCount();
    }
    
    // Mettre à jour le compteur de caractères en temps réel
    function updateCharacterCount() {
        const textarea = document.getElementById('redflag-reason');
        const charCount = document.getElementById('char-count');
        const charValidation = document.getElementById('char-validation');
        
        if (!textarea || !charCount || !charValidation) return;
        
        const length = textarea.value.length;
        
        // Mettre à jour le compteur
        charCount.textContent = length + (length <= 1 ? ' caractère' : ' caractères');
        
        // Déterminer le message et la couleur
        if (length < 50) {
            charValidation.textContent = 'Explication non détaillée';
            charValidation.style.color = '#dc2626'; // Rouge
        } else if (length >= 50 && length < 150) {
            charValidation.textContent = 'Explication peu détaillée';
            charValidation.style.color = '#f59e0b'; // Orange
        } else {
            charValidation.textContent = 'Explication bien détaillé';
            charValidation.style.color = '#16a34a'; // Vert
        }
    }
    
    // Soumettre le signalement
    function submitRedFlag(event, applicationId) {
        event.preventDefault();
        
        const subject = document.getElementById('redflag-subject').value;
        const reason = document.getElementById('redflag-reason').value;
        
        // Validation
        if (!subject) {
            alert('Veuillez sélectionner un motif de signalement');
            return;
        }
        
        if (reason.length < 50) {
            alert('La description doit contenir au moins 50 caractères');
            return;
        }
        
        if (!confirm('Êtes-vous sûr de vouloir signaler cette entreprise ? Cette action est irréversible et votre candidature sera annulée.')) {
            return;
        }
        
        // Afficher un loader
        openPopup('popup-redflag', '<div class="popup-loader"><div class="spinner"></div><p>Envoi du signalement...</p></div>');
        
        // Envoyer le signalement
        fetch(`${baseUrl}/function/sendRedFlagMail.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                application_id: applicationId,
                subject: subject,
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closePopup('popup-redflag');
                alert('✅ Signalement envoyé avec succès. Notre équipe va examiner votre demande. Votre candidature a été annulée.');
                // Recharger la liste des candidatures
                loadApplications();
            } else {
                throw new Error(data.error || 'Erreur inconnue');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('❌ Erreur lors de l\'envoi du signalement: ' + error.message);
            closePopup('popup-redflag');
        });
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
