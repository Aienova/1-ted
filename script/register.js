/**
 * Affiche une popup de création de compte pour le candidat
 * Récupère automatiquement nom, prénom, email, téléphone du CV
 * Demande uniquement login et mot de passe (avec confirmation)
 * 
 * @param {number} candidateId - L'ID du candidat qui vient d'être créé
 * @returns {Promise} - Résout quand le compte est créé, rejette si annulé
 */
async function registerPopup(candidateId) {
    return new Promise((resolve, reject) => {
        // Récupérer les données du candidat depuis le DOM
        const firstName = document.querySelector('[data-table="identity"][data-column="first_name"]')?.getAttribute('data-value') || '';
        const lastName = document.querySelector('[data-table="identity"][data-column="last_name"]')?.getAttribute('data-value') || '';
        const email = document.querySelector('[data-table="identity"][data-column="email"]')?.getAttribute('data-value') || '';
        const phone = document.querySelector('[data-table="identity"][data-column="phone"]')?.getAttribute('data-value') || '';

        // Créer la popup
        const popup = document.createElement('div');
        popup.className = 'register-popup-overlay';
        popup.innerHTML = `
            <div class="register-popup">
                <div class="register-popup-header">
                    <h2>🎉 CV enregistré avec succès !</h2>
                    <p>Créez maintenant votre compte utilisateur pour accéder à votre espace candidat</p>
                </div>
                <form id="register-form" class="register-popup-content">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="register-firstname">Prénom *</label>
                            <input 
                                type="text" 
                                id="register-firstname" 
                                class="form-input" 
                                value="${firstName}"
                                readonly
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="register-lastname">Nom *</label>
                            <input 
                                type="text" 
                                id="register-lastname" 
                                class="form-input" 
                                value="${lastName}"
                                readonly
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="register-email">Email *</label>
                            <input 
                                type="email" 
                                id="register-email" 
                                class="form-input" 
                                value="${email}"
                                readonly
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="register-phone">Téléphone *</label>
                            <input 
                                type="tel" 
                                id="register-phone" 
                                class="form-input" 
                                value="${phone}"
                                readonly
                                required
                            >
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    <div class="form-group">
                        <label for="register-login">Identifiant de connexion *</label>
                        <input 
                            type="text" 
                            id="register-login" 
                            class="form-input" 
                            placeholder="Ex: john.doe"
                            required
                            autocomplete="off"
                        >
                        <small class="form-help">Choisissez un identifiant unique pour vous connecter</small>
                    </div>

                    <div class="form-group">
                        <label for="register-password">Mot de passe *</label>
                        <input 
                            type="password" 
                            id="register-password" 
                            class="form-input" 
                            placeholder="Minimum 8 caractères"
                            required
                            minlength="8"
                            autocomplete="new-password"
                        >
                        <small class="form-help">Minimum 8 caractères</small>
                    </div>

                    <div class="form-group">
                        <label for="register-password-confirm">Confirmer le mot de passe *</label>
                        <input 
                            type="password" 
                            id="register-password-confirm" 
                            class="form-input" 
                            placeholder="Retapez votre mot de passe"
                            required
                            minlength="8"
                            autocomplete="new-password"
                        >
                    </div>

                    <div id="register-error" class="register-error" style="display: none;"></div>
                    <div id="register-success" class="register-success" style="display: none;"></div>
                </form>
                <div class="register-popup-footer">
                    <button type="button" class="register-btn register-btn-cancel" id="register-cancel-btn">
                        ❌ Annuler et supprimer
                    </button>
                    <button type="submit" form="register-form" class="register-btn register-btn-save" id="register-submit-btn">
                        ✅ Créer mon compte
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(popup);

        // Animation d'apparition
        setTimeout(() => {
            popup.classList.add('show');
        }, 10);

        // Gestion du formulaire
        const form = document.getElementById('register-form');
        const cancelBtn = document.getElementById('register-cancel-btn');
        const submitBtn = document.getElementById('register-submit-btn');
        const errorDiv = document.getElementById('register-error');
        const successDiv = document.getElementById('register-success');

        // Fonction pour afficher une erreur
        function showError(message) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            successDiv.style.display = 'none';
        }

        // Fonction pour afficher un succès
        function showSuccess(message) {
            successDiv.textContent = message;
            successDiv.style.display = 'block';
            errorDiv.style.display = 'none';
        }

        // Fonction pour fermer la popup
        function closePopup() {
            popup.classList.remove('show');
            setTimeout(() => {
                popup.remove();
            }, 300);
        }

        // Gestion de l'annulation
        cancelBtn.addEventListener('click', async () => {
            if (!confirm('⚠️ Êtes-vous sûr de vouloir annuler ? Le CV créé sera supprimé définitivement.')) {
                return;
            }

            try {
                cancelBtn.disabled = true;
                cancelBtn.textContent = '🗑️ Suppression...';

                // Supprimer le candidat et toutes ses données (clés étrangères)
                const response = await fetch(getFunctionUrl('deleteData.php'), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        table: 'candidate',
                        id: candidateId
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    console.log('✅ Candidat supprimé avec succès');
                    closePopup();
                    
                    // Recharger la page pour réinitialiser le formulaire
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                    
                    reject(new Error('Inscription annulée par l\'utilisateur'));
                } else {
                    showError('Erreur lors de la suppression : ' + result.message);
                    cancelBtn.disabled = false;
                    cancelBtn.textContent = '❌ Annuler et supprimer';
                }
            } catch (error) {
                console.error('❌ Erreur suppression:', error);
                showError('Erreur lors de la suppression : ' + error.message);
                cancelBtn.disabled = false;
                cancelBtn.textContent = '❌ Annuler et supprimer';
            }
        });

        // Gestion de la soumission du formulaire
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Récupérer les valeurs
            const login = document.getElementById('register-login').value.trim();
            const password = document.getElementById('register-password').value;
            const passwordConfirm = document.getElementById('register-password-confirm').value;

            // Validation
            if (!login) {
                showError('Veuillez renseigner un identifiant de connexion');
                return;
            }

            if (password.length < 8) {
                showError('Le mot de passe doit contenir au moins 8 caractères');
                return;
            }

            if (password !== passwordConfirm) {
                showError('Les mots de passe ne correspondent pas');
                return;
            }

            try {
                submitBtn.disabled = true;
                submitBtn.textContent = '⏳ Création du compte...';
                errorDiv.style.display = 'none';

                // Créer le compte utilisateur
                const response = await fetch(getFunctionUrl('createAccount.php'), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        phone: phone,
                        login: login,
                        password: password,
                        role: 'candidate',
                        candidate_id: candidateId
                    })
                });

                const result = await response.json();

                if (result.success) {
                    console.log('✅ Compte créé avec succès:', result);
                    showSuccess('✅ Compte créé avec succès ! Un email de vérification vous a été envoyé à votre adresse email.');
                    
                    // Envoyer l'email de vérification
                    try {
                        const accountId = result.account_id || result.id;
                        const currentLang = window.APP_LANG || 'fr';
                        
                        const verificationResponse = await fetch(getFunctionUrl('sendVerificationEmail.php'), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                account_id: accountId,
                                lang: currentLang
                            })
                        });
                        
                        const verificationResult = await verificationResponse.json();
                        if (verificationResult.success) {
                            console.log('✅ Email de vérification envoyé:', verificationResult);
                        } else {
                            console.warn('⚠️ Erreur envoi email de vérification:', verificationResult.message);
                        }
                    } catch (emailError) {
                        console.error('❌ Erreur lors de l\'envoi de l\'email de vérification:', emailError);
                    }
                    
                    // Fermer la popup après 2 secondes
                    setTimeout(() => {
                        closePopup();
                        resolve(result); // Résoudre la promesse pour continuer le script
                    }, 2000);
                } else {
                    showError(result.message || 'Erreur lors de la création du compte');
                    submitBtn.disabled = false;
                    submitBtn.textContent = '✅ Créer mon compte';
                }
            } catch (error) {
                console.error('❌ Erreur création compte:', error);
                showError('Erreur lors de la création du compte : ' + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = '✅ Créer mon compte';
            }
        });

        // Empêcher la fermeture en cliquant en dehors
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                e.stopPropagation();
            }
        });
    });
}

/**
 * Affiche une popup de création de compte pour l'entreprise/recruteur
 * Récupère automatiquement les informations de la société et du contact
 * Demande login, mot de passe et SIRET
 * 
 * @param {number} companyId - L'ID de l'entreprise qui vient d'être créée
 * @param {number} contactId - L'ID du contact de l'entreprise
 * @returns {Promise} - Résout quand le compte est créé, rejette si annulé
 */
async function registerPopupCompany(companyId, contactId) {
    // Debug: vérifier si un logo est en attente
    console.log('🎨 [Register Popup] Vérification du logo en attente...');
    console.log('🎨 window.pendingCompanyLogo:', window.pendingCompanyLogo ? 'PRÉSENT' : 'ABSENT');
    if (window.pendingCompanyLogo) {
        console.log('🎨 Type de logo:', window.pendingCompanyLogo.startsWith('data:') ? 'Base64' : 'URL');
        console.log('🎨 Aperçu:', window.pendingCompanyLogo.substring(0, 100) + '...');
    }
    
    // Vérifier si une popup existe déjà et la réafficher
    if (window.currentRegisterPopup) {
        // Créer une nouvelle Promise qui attend l'action de l'utilisateur
        return new Promise((resolve, reject) => {
            // Mettre à jour les valeurs avec les données actuelles du DOM
            const companyName = document.querySelector('[data-table="company"][data-column="name"]')?.getAttribute('data-value') || '';
            const companyDomain = document.querySelector('[data-table="company"][data-column="domain"]')?.getAttribute('data-value') || '';
            const firstName = document.querySelector('[data-table="contact"][data-column="first_name"]')?.getAttribute('data-value') || '';
            const lastName = document.querySelector('[data-table="contact"][data-column="last_name"]')?.getAttribute('data-value') || '';
            const email = document.querySelector('[data-table="contact"][data-column="email"]')?.getAttribute('data-value') || '';
            const phone = document.querySelector('[data-table="contact"][data-column="phone"]')?.getAttribute('data-value') || '';
            
            // Mettre à jour les champs dans la popup avec les nouvelles valeurs
            const companyNameInput = window.currentRegisterPopup.querySelector('#register-company-name');
            const companyDomainInput = window.currentRegisterPopup.querySelector('#register-company-domain');
            const firstNameInput = window.currentRegisterPopup.querySelector('#register-company-firstname');
            const lastNameInput = window.currentRegisterPopup.querySelector('#register-company-lastname');
            const emailInput = window.currentRegisterPopup.querySelector('#register-company-email');
            const phoneInput = window.currentRegisterPopup.querySelector('#register-company-phone');
            
            if (companyNameInput) companyNameInput.value = companyName;
            if (companyDomainInput) companyDomainInput.value = companyDomain;
            if (firstNameInput) firstNameInput.value = firstName;
            if (lastNameInput) lastNameInput.value = lastName;
            if (emailInput) emailInput.value = email;
            if (phoneInput) phoneInput.value = phone;
            
            // Forcer le select à être interactif
            if (companyDomainInput) {
                companyDomainInput.removeAttribute('disabled');
                companyDomainInput.removeAttribute('readonly');
                companyDomainInput.style.pointerEvents = 'auto';
                companyDomainInput.style.cursor = 'pointer';
                companyDomainInput.style.opacity = '1';
                companyDomainInput.style.backgroundColor = 'white';
            }
            
            // Mettre à jour les fonctions resolve/reject pour cette nouvelle Promise
            window.currentRegisterPopup.resolveFunction = resolve;
            window.currentRegisterPopup.rejectFunction = reject;
            
            // Réafficher la popup
            window.currentRegisterPopup.style.display = 'flex';
            setTimeout(() => {
                window.currentRegisterPopup.classList.add('show');
            }, 10);
        });
    }
    
    return new Promise((resolve, reject) => {
        // Récupérer les données du formulaire
        const companyName = document.querySelector('[data-table="company"][data-column="name"]')?.getAttribute('data-value') || '';
        const companyDomain = document.querySelector('[data-table="company"][data-column="domain"]')?.getAttribute('data-value') || '';
        const firstName = document.querySelector('[data-table="contact"][data-column="first_name"]')?.getAttribute('data-value') || '';
        const lastName = document.querySelector('[data-table="contact"][data-column="last_name"]')?.getAttribute('data-value') || '';
        const email = document.querySelector('[data-table="contact"][data-column="email"]')?.getAttribute('data-value') || '';
        const phone = document.querySelector('[data-table="contact"][data-column="phone"]')?.getAttribute('data-value') || '';
        const country = document.querySelector('[data-table="company"][data-column="country"]')?.getAttribute('data-value') || '';

        // Créer la popup
        const popup = document.createElement('div');
        popup.className = 'register-popup-overlay';
        popup.innerHTML = `
            <div class="register-popup">
                <div class="register-popup-header">
                    <h2>🎉 Offre d'emploi créée avec succès !</h2>
                    <p>Créez maintenant votre compte recruteur pour gérer vos offres et candidatures</p>
                </div>
                <form id="register-company-form" class="register-popup-content">
                    <div class="form-group">
                        <label for="register-company-name">Entreprise *</label>
                        <input 
                            type="text" 
                            id="register-company-name" 
                            class="form-input" 
                            value="${companyName}"
                            required
                            placeholder="Nom de l'entreprise"
                        >
                        <small class="form-help">Vous pouvez modifier ce nom si nécessaire</small>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="register-company-domain">Secteur d'activité *</label>
                            <select 
                                id="register-company-domain" 
                                class="form-input" 
                                required
                            >
                                <option value="">-- Sélectionnez un secteur --</option>
                                <option value="Agriculture" ${companyDomain === 'Agriculture' ? 'selected' : ''}>Agriculture</option>
                                <option value="Agroalimentaire" ${companyDomain === 'Agroalimentaire' ? 'selected' : ''}>Agroalimentaire</option>
                                <option value="Automobile" ${companyDomain === 'Automobile' ? 'selected' : ''}>Automobile</option>
                                <option value="Banque/Assurance" ${companyDomain === 'Banque/Assurance' ? 'selected' : ''}>Banque/Assurance</option>
                                <option value="BTP/Construction" ${companyDomain === 'BTP/Construction' ? 'selected' : ''}>BTP/Construction</option>
                                <option value="Commerce/Distribution" ${companyDomain === 'Commerce/Distribution' ? 'selected' : ''}>Commerce/Distribution</option>
                                <option value="Conseil" ${companyDomain === 'Conseil' ? 'selected' : ''}>Conseil</option>
                                <option value="Culture/Art" ${companyDomain === 'Culture/Art' ? 'selected' : ''}>Culture/Art</option>
                                <option value="Énergie" ${companyDomain === 'Énergie' ? 'selected' : ''}>Énergie</option>
                                <option value="Enseignement" ${companyDomain === 'Enseignement' ? 'selected' : ''}>Enseignement</option>
                                <option value="Environnement" ${companyDomain === 'Environnement' ? 'selected' : ''}>Environnement</option>
                                <option value="Hôtellerie/Restauration" ${companyDomain === 'Hôtellerie/Restauration' ? 'selected' : ''}>Hôtellerie/Restauration</option>
                                <option value="Immobilier" ${companyDomain === 'Immobilier' ? 'selected' : ''}>Immobilier</option>
                                <option value="Industrie" ${companyDomain === 'Industrie' ? 'selected' : ''}>Industrie</option>
                                <option value="Informatique/Numérique" ${companyDomain === 'Informatique/Numérique' ? 'selected' : ''}>Informatique/Numérique</option>
                                <option value="Logistique/Transport" ${companyDomain === 'Logistique/Transport' ? 'selected' : ''}>Logistique/Transport</option>
                                <option value="Marketing/Communication" ${companyDomain === 'Marketing/Communication' ? 'selected' : ''}>Marketing/Communication</option>
                                <option value="Médias" ${companyDomain === 'Médias' ? 'selected' : ''}>Médias</option>
                                <option value="Santé" ${companyDomain === 'Santé' ? 'selected' : ''}>Santé</option>
                                <option value="Services" ${companyDomain === 'Services' ? 'selected' : ''}>Services</option>
                                <option value="Sport/Loisirs" ${companyDomain === 'Sport/Loisirs' ? 'selected' : ''}>Sport/Loisirs</option>
                                <option value="Télécommunications" ${companyDomain === 'Télécommunications' ? 'selected' : ''}>Télécommunications</option>
                                <option value="Tourisme" ${companyDomain === 'Tourisme' ? 'selected' : ''}>Tourisme</option>
                                <option value="Autre" ${companyDomain === 'Autre' ? 'selected' : ''}>Autre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="register-siret">Numéro SIRET *</label>
                            <input 
                                type="text" 
                                id="register-siret" 
                                class="form-input" 
                                placeholder="Ex: 123 456 789 00010"
                                required
                                pattern="[0-9\\s]{14,17}"
                                maxlength="17"
                            >
                            <small class="form-help">14 chiffres requis (entreprises françaises)</small>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="register-company-firstname">Prénom du contact *</label>
                            <input 
                                type="text" 
                                id="register-company-firstname" 
                                class="form-input" 
                                value="${firstName}"
                                required
                                placeholder="Prénom"
                            >
                        </div>
                        <div class="form-group">
                            <label for="register-company-lastname">Nom du contact *</label>
                            <input 
                                type="text" 
                                id="register-company-lastname" 
                                class="form-input" 
                                value="${lastName}"
                                required
                                placeholder="Nom"
                            >
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="register-company-email">Email *</label>
                            <input 
                                type="email" 
                                id="register-company-email" 
                                class="form-input" 
                                value="${email}"
                                required
                                placeholder="contact@entreprise.com"
                            >
                        </div>
                        <div class="form-group">
                            <label for="register-company-phone">Téléphone *</label>
                            <input 
                                type="tel" 
                                id="register-company-phone" 
                                class="form-input" 
                                value="${phone}"
                                required
                                placeholder="+33 6 12 34 56 78"
                            >
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    <div class="form-group">
                        <label for="register-company-login">Identifiant de connexion *</label>
                        <input 
                            type="text" 
                            id="register-company-login" 
                            class="form-input" 
                            placeholder="Ex: ${companyName.toLowerCase().replace(/\s+/g, '.')}"
                            required
                            autocomplete="off"
                        >
                        <small class="form-help">Choisissez un identifiant unique pour vous connecter</small>
                    </div>

                    <div class="form-group">
                        <label for="register-company-password">Mot de passe *</label>
                        <input 
                            type="password" 
                            id="register-company-password" 
                            class="form-input" 
                            placeholder="Minimum 8 caractères"
                            required
                            minlength="8"
                            autocomplete="new-password"
                        >
                        <small class="form-help">Minimum 8 caractères</small>
                    </div>

                    <div class="form-group">
                        <label for="register-company-password-confirm">Confirmer le mot de passe *</label>
                        <input 
                            type="password" 
                            id="register-company-password-confirm" 
                            class="form-input" 
                            placeholder="Retapez votre mot de passe"
                            required
                            minlength="8"
                            autocomplete="new-password"
                        >
                    </div>
                    
                    <div class="form-divider"></div>
                    
                    <div class="form-group">
                        <label>Logo de l'entreprise ${window.pendingCompanyLogo ? '✓' : '(optionnel)'}</label>
                        ${window.pendingCompanyLogo ? `
                            <div style="background: #d1fae5; padding: 12px; border-radius: 8px; margin-bottom: 12px; color: #065f46; display: flex; align-items: center; gap: 12px;">
                                <img src="${window.pendingCompanyLogo}" alt="Logo" style="width: 60px; height: 60px; object-fit: contain; border-radius: 4px; background: white; padding: 4px;">
                                <div style="flex: 1;">
                                    <div style="font-weight: 500;">✓ Logo déjà téléchargé</div>
                                    <div style="font-size: 0.875rem; margin-top: 4px;">Le logo que vous avez sélectionné dans le formulaire a été enregistré avec succès.</div>
                                </div>
                            </div>
                        ` : `
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                <input 
                                    type="file" 
                                    id="register-company-logo" 
                                    accept="image/png,image/jpeg,image/jpg"
                                    style="display: none;"
                                >
                                <button 
                                    type="button" 
                                    id="register-company-logo-btn"
                                    class="form-input"
                                    style="background: #3b82f6; color: white; border: none; cursor: pointer; padding: 12px; border-radius: 6px; font-weight: 500;"
                                >
                                    📁 Sélectionner un logo
                                </button>
                                <div id="register-company-logo-preview" style="display: none; align-items: center; gap: 12px; padding: 12px; background: #f3f4f6; border-radius: 6px;">
                                    <img id="register-company-logo-img" src="" alt="Logo" style="width: 80px; height: 80px; object-fit: contain; border-radius: 4px; background: white; padding: 4px;">
                                    <div style="flex: 1;">
                                        <div id="register-company-logo-name" style="font-weight: 500; color: #1f2937;"></div>
                                        <div id="register-company-logo-size" style="font-size: 0.875rem; color: #6b7280; margin-top: 4px;"></div>
                                    </div>
                                    <button 
                                        type="button" 
                                        id="register-company-logo-remove"
                                        style="background: #ef4444; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 0.875rem;"
                                    >
                                        ✕ Supprimer
                                    </button>
                                </div>
                            </div>
                            <small class="form-help">Format accepté : PNG, JPG (max 5Mo)</small>
                        `}
                    </div>

                    <div id="register-company-error" class="register-error" style="display: none;"></div>
                    <div id="register-company-success" class="register-success" style="display: none;"></div>
                </form>
                <div class="register-popup-footer">
                    <button type="button" class="register-btn register-btn-cancel" id="register-company-back-btn">
                        ⬅️ Revenir en arrière
                    </button>
                    <button type="submit" form="register-company-form" class="register-btn register-btn-save" id="register-company-submit-btn">
                        ✅ Créer mon compte
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(popup);
        
        // Stocker la popup globalement pour pouvoir la réafficher
        window.currentRegisterPopup = popup;
        
        // Stocker les fonctions resolve/reject sur la popup elle-même
        popup.resolveFunction = resolve;
        popup.rejectFunction = reject;
        
        // Stocker les valeurs originales comme attributs data pour la comparaison lors du submit
        popup.setAttribute('data-original-company-name', companyName);
        popup.setAttribute('data-original-company-domain', companyDomain);
        popup.setAttribute('data-original-first-name', firstName);
        popup.setAttribute('data-original-last-name', lastName);
        popup.setAttribute('data-original-email', email);
        popup.setAttribute('data-original-phone', phone);

        // Animation d'apparition
        setTimeout(() => {
            popup.classList.add('show');
            
            // Forcer le select à être interactif
            const domainSelect = document.getElementById('register-company-domain');
            if (domainSelect) {
                domainSelect.removeAttribute('disabled');
                domainSelect.removeAttribute('readonly');
                domainSelect.style.pointerEvents = 'auto';
                domainSelect.style.cursor = 'pointer';
                domainSelect.style.opacity = '1';
                domainSelect.style.backgroundColor = 'white';
            }
            
            // Gérer le sélecteur de logo (seulement si pas déjà uploadé)
            if (!window.pendingCompanyLogo) {
                const logoInput = document.getElementById('register-company-logo');
                const logoBtn = document.getElementById('register-company-logo-btn');
                const logoPreview = document.getElementById('register-company-logo-preview');
                const logoImg = document.getElementById('register-company-logo-img');
                const logoName = document.getElementById('register-company-logo-name');
                const logoSize = document.getElementById('register-company-logo-size');
                const logoRemove = document.getElementById('register-company-logo-remove');
                
                if (logoBtn && logoInput) {
                    // Clic sur le bouton = ouvrir le sélecteur
                    logoBtn.addEventListener('click', () => {
                        logoInput.click();
                    });
                    
                    // Quand un fichier est sélectionné
                    logoInput.addEventListener('change', (e) => {
                        const file = e.target.files[0];
                        if (file) {
                            // Vérifier la taille (max 5Mo)
                            if (file.size > 5 * 1024 * 1024) {
                                alert('❌ Le fichier est trop volumineux. Taille maximale : 5Mo');
                                logoInput.value = '';
                                return;
                            }
                            
                            // Vérifier le type
                            if (!file.type.match(/image\/(png|jpeg|jpg)/)) {
                                alert('❌ Format non supporté. Utilisez PNG ou JPG');
                                logoInput.value = '';
                                return;
                            }
                            
                            // Afficher l'aperçu
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                logoImg.src = e.target.result;
                                logoName.textContent = file.name;
                                logoSize.textContent = (file.size / 1024).toFixed(1) + ' Ko';
                                logoPreview.style.display = 'flex';
                                logoBtn.textContent = '✓ Logo sélectionné';
                                logoBtn.style.background = '#10b981';
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                    
                    // Bouton supprimer
                    if (logoRemove) {
                        logoRemove.addEventListener('click', () => {
                            logoInput.value = '';
                            logoPreview.style.display = 'none';
                            logoBtn.textContent = '📁 Sélectionner un logo';
                            logoBtn.style.background = '#3b82f6';
                        });
                    }
                }
            } else {
                console.log('ℹ️ Logo déjà uploadé, pas besoin de sélecteur');
            }
        }, 10);

        // Gestion du formulaire
        const form = document.getElementById('register-company-form');
        const backBtn = document.getElementById('register-company-back-btn');
        const submitBtn = document.getElementById('register-company-submit-btn');
        const errorDiv = document.getElementById('register-company-error');
        const successDiv = document.getElementById('register-company-success');

        // Fonction pour afficher une erreur
        function showError(message) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            successDiv.style.display = 'none';
        }

        // Fonction pour afficher un succès
        function showSuccess(message) {
            successDiv.textContent = message;
            successDiv.style.display = 'block';
            errorDiv.style.display = 'none';
        }

        // Fonction pour masquer la popup (sans la détruire)
        function hidePopup() {
            popup.classList.remove('show');
            setTimeout(() => {
                popup.style.display = 'none';
            }, 300);
        }
        
        // Fonction pour fermer définitivement la popup
        function closePopup() {
            popup.classList.remove('show');
            setTimeout(() => {
                popup.remove();
                window.currentRegisterPopup = null;
            }, 300);
        }

        // Gestion du bouton "Revenir en arrière"
        backBtn.addEventListener('click', () => {
            hidePopup();
            // Utiliser la fonction resolve stockée sur la popup
            if (popup.resolveFunction) {
                popup.resolveFunction({ skipped: true });
            }
        });

        // Gestion de la soumission du formulaire
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Récupérer les valeurs
            const login = document.getElementById('register-company-login').value.trim();
            const password = document.getElementById('register-company-password').value;
            const passwordConfirm = document.getElementById('register-company-password-confirm').value;
            const siretInput = document.getElementById('register-siret');
            const siret = siretInput ? siretInput.value.replace(/\s+/g, '') : '';
            const domain = document.getElementById('register-company-domain').value;

            // Validation
            if (!login) {
                showError('Veuillez renseigner un identifiant de connexion');
                return;
            }

            if (!domain) {
                showError('Veuillez sélectionner un secteur d\'activité');
                return;
            }

            if (password.length < 8) {
                showError('Le mot de passe doit contenir au moins 8 caractères');
                return;
            }

            if (password !== passwordConfirm) {
                showError('Les mots de passe ne correspondent pas');
                return;
            }

            // Valider le SIRET
            if (siret && siret.length !== 14) {
                showError('Le numéro SIRET doit contenir exactement 14 chiffres');
                return;
            }

            try {
                submitBtn.disabled = true;
                submitBtn.textContent = '⏳ Création du compte...';
                errorDiv.style.display = 'none';

                // Récupérer les valeurs actuelles des champs (au cas où elles ont été modifiées)
                const currentCompanyName = document.getElementById('register-company-name').value;
                const currentCompanyDomain = document.getElementById('register-company-domain').value;
                const currentFirstName = document.getElementById('register-company-firstname').value;
                const currentLastName = document.getElementById('register-company-lastname').value;
                const currentEmail = document.getElementById('register-company-email').value;
                const currentPhone = document.getElementById('register-company-phone').value;
                
                // Récupérer les valeurs originales depuis les attributs data de la popup
                const originalCompanyName = popup.getAttribute('data-original-company-name') || '';
                const originalCompanyDomain = popup.getAttribute('data-original-company-domain') || '';
                const originalFirstName = popup.getAttribute('data-original-first-name') || '';
                const originalLastName = popup.getAttribute('data-original-last-name') || '';
                const originalEmail = popup.getAttribute('data-original-email') || '';
                const originalPhone = popup.getAttribute('data-original-phone') || '';

                // Mettre à jour les données de la company si le nom ou le secteur a changé
                if ((currentCompanyName && currentCompanyName !== originalCompanyName) || (currentCompanyDomain && currentCompanyDomain !== originalCompanyDomain)) {
                    const companyData = {};
                    if (currentCompanyName !== originalCompanyName) {
                        companyData.name = currentCompanyName;
                    }
                    if (currentCompanyDomain !== originalCompanyDomain) {
                        companyData.domain = currentCompanyDomain;
                    }
                    await fetch(getFunctionUrl('updateData.php'), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            table: 'company',
                            id: companyId,
                            data: companyData
                        })
                    });
                }

                // 📝 ÉTAPE 1: Créer/Mettre à jour le contact et TOUJOURS le lier à la company
                let finalContactId = contactId;
                console.log('📝 Gestion du contact et liaison à l\'entreprise...');
                console.log('📝 companyId:', companyId, 'contactId:', contactId);
                
                if (!contactId) {
                    // Créer un nouveau contact ET le lier à l'entreprise
                    console.log('📝 Création d\'un nouveau contact avec linkAccountContact...');
                    const contactResponse = await fetch(getFunctionUrl('linkAccountContact.php'), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            company_id: companyId,
                            first_name: currentFirstName,
                            last_name: currentLastName,
                            email: currentEmail,
                            phone: currentPhone
                        })
                    });
                    
                    const contactResult = await contactResponse.json();
                    console.log('📝 Résultat création contact:', contactResult);
                    
                    if (contactResult.success) {
                        finalContactId = contactResult.contactId;
                        console.log('✅ Contact créé et lié, ID:', finalContactId, 'Company:', companyId);
                    } else {
                        console.error('❌ Erreur création contact:', contactResult.message);
                        throw new Error('Impossible de créer le contact: ' + contactResult.message);
                    }
                } else {
                    // Le contact existe déjà, mettre à jour ses informations
                    console.log('📝 Mise à jour du contact existant ID:', contactId);
                    await fetch(getFunctionUrl('updateData.php'), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            table: 'contact',
                            id: contactId,
                            data: {
                                first_name: currentFirstName,
                                last_name: currentLastName,
                                email: currentEmail,
                                phone: currentPhone
                            }
                        })
                    });
                    console.log('✅ Contact mis à jour');
                    
                    // IMPORTANT: Lier le contact existant à la company
                    console.log('📝 Liaison du contact', contactId, 'à la company', companyId);
                    await fetch(getFunctionUrl('updateData.php'), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            table: 'company',
                            id: companyId,
                            data: {
                                contact_id: contactId
                            }
                        })
                    });
                    console.log('✅ Contact lié à la company avec succès');
                    finalContactId = contactId;
                }

                // Si SIRET fourni, mettre à jour la société
                if (siret) {
                    await fetch(getFunctionUrl('updateData.php'), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            table: 'company',
                            id: companyId,
                            data: {
                                nation_register_number: siret
                            }
                        })
                    });
                }

                // 🖼️ ÉTAPE 2: Uploader le logo avec linkLogoToCompanyDuringRegistration
                console.log('🖼️ Gestion du logo...');
                
                // Récupérer le logo depuis plusieurs sources possibles
                let logoToUpload = null;
                const logoInput = document.getElementById('register-company-logo');
                
                // Priorité 1: window.pendingCompanyLogo (logo depuis job-title)
                if (window.pendingCompanyLogo) {
                    logoToUpload = window.pendingCompanyLogo;
                    console.log('🖼️ Logo récupéré depuis window.pendingCompanyLogo');
                }
                // Priorité 2: logo uploadé dans la popup
                else if (logoInput && logoInput.files && logoInput.files[0]) {
                    console.log('🖼️ Logo uploadé dans la popup, conversion en base64...');
                    const logoFile = logoInput.files[0];
                    
                    // Convertir en base64
                    await new Promise((resolve) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            logoToUpload = e.target.result;
                            console.log('🖼️ Logo converti en base64');
                            resolve();
                        };
                        reader.onerror = () => resolve();
                        reader.readAsDataURL(logoFile);
                    });
                }
                
                // Uploader le logo si disponible
                if (logoToUpload) {
                    try {
                        console.log('🖼️ Upload du logo via linkLogoToCompanyDuringRegistration...');
                        const logoResponse = await fetch(getFunctionUrl('linkLogoToCompanyDuringRegistration.php'), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                company_id: companyId,
                                logo_data: logoToUpload
                            })
                        });
                        
                        const logoResult = await logoResponse.json();
                        console.log('🖼️ Résultat upload logo:', logoResult);
                        
                        if (logoResult.success) {
                            console.log('✅ Logo uploadé avec succès:', logoResult.logoUrl);
                        } else {
                            console.warn('⚠️ Erreur upload logo:', logoResult.message);
                        }
                    } catch (error) {
                        console.error('⚠️ Erreur lors de l\'upload du logo:', error);
                        // Ne pas bloquer la création du compte si l'upload échoue
                    }
                } else {
                    console.log('ℹ️ Pas de logo à uploader');
                }
                
                // Nettoyer le logo en attente
                if (window.pendingCompanyLogo) {
                    delete window.pendingCompanyLogo;
                }
                
                // ✅ ÉTAPE 3: Créer le compte utilisateur
                const response = await fetch(getFunctionUrl('createAccount.php'), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        first_name: currentFirstName,
                        last_name: currentLastName,
                        email: currentEmail,
                        phone: currentPhone,
                        login: login,
                        password: password,
                        role: 'company',
                        company_id: companyId
                    })
                });

                const result = await response.json();

                if (result.success) {
                    console.log('✅ Compte entreprise créé avec succès:', result);
                    showSuccess('✅ Compte créé avec succès ! Un email de vérification vous a été envoyé.');
                    
                    // Envoyer l'email de vérification
                    try {
                        const accountId = result.account_id || result.id;
                        const currentLang = window.APP_LANG || 'fr';
                        
                        const verificationResponse = await fetch(getFunctionUrl('sendVerificationEmail.php'), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                account_id: accountId,
                                lang: currentLang
                            })
                        });
                        
                        const verificationResult = await verificationResponse.json();
                        if (verificationResult.success) {
                            console.log('✅ Email de vérification envoyé:', verificationResult);
                        } else {
                            console.warn('⚠️ Erreur envoi email de vérification:', verificationResult.message);
                        }
                    } catch (emailError) {
                        console.error('❌ Erreur lors de l\'envoi de l\'email de vérification:', emailError);
                    }
                    
                    // Fermer la popup après 2 secondes
                    setTimeout(() => {
                        closePopup();
                        // Utiliser la fonction resolve stockée sur la popup
                        if (popup.resolveFunction) {
                            popup.resolveFunction(result);
                        }
                    }, 2000);
                } else {
                    showError(result.message || 'Erreur lors de la création du compte');
                    submitBtn.disabled = false;
                    submitBtn.textContent = '✅ Créer mon compte';
                }
            } catch (error) {
                console.error('❌ Erreur création compte:', error);
                showError('Erreur lors de la création du compte : ' + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = '✅ Créer mon compte';
            }
        });

        // Empêcher la fermeture en cliquant en dehors
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                e.stopPropagation();
            }
        });
    });
}
