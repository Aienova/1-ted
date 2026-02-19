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
                    showSuccess('✅ Compte créé avec succès ! Redirection...');
                    
                    // Fermer la popup après 1 seconde
                    setTimeout(() => {
                        closePopup();
                        resolve(result); // Résoudre la promesse pour continuer le script
                    }, 1000);
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
    return new Promise((resolve, reject) => {
        // Récupérer les données du formulaire
        const companyName = document.querySelector('[data-table="company"][data-column="name"]')?.getAttribute('data-value') || '';
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
                            readonly
                            required
                        >
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="register-company-firstname">Prénom du contact *</label>
                            <input 
                                type="text" 
                                id="register-company-firstname" 
                                class="form-input" 
                                value="${firstName}"
                                readonly
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="register-company-lastname">Nom du contact *</label>
                            <input 
                                type="text" 
                                id="register-company-lastname" 
                                class="form-input" 
                                value="${lastName}"
                                readonly
                                required
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
                                readonly
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="register-company-phone">Téléphone *</label>
                            <input 
                                type="tel" 
                                id="register-company-phone" 
                                class="form-input" 
                                value="${phone}"
                                readonly
                                required
                            >
                        </div>
                    </div>

                    ${country.toLowerCase().includes('france') || country.toLowerCase() === 'fr' ? `
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
                            <small class="form-help">14 chiffres requis pour les entreprises françaises</small>
                        </div>
                    ` : ''}

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

                    <div id="register-company-error" class="register-error" style="display: none;"></div>
                    <div id="register-company-success" class="register-success" style="display: none;"></div>
                </form>
                <div class="register-popup-footer">
                    <button type="button" class="register-btn register-btn-cancel" id="register-company-skip-btn">
                        ⏭️ Passer cette étape
                    </button>
                    <button type="submit" form="register-company-form" class="register-btn register-btn-save" id="register-company-submit-btn">
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
        const form = document.getElementById('register-company-form');
        const skipBtn = document.getElementById('register-company-skip-btn');
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

        // Fonction pour fermer la popup
        function closePopup() {
            popup.classList.remove('show');
            setTimeout(() => {
                popup.remove();
            }, 300);
        }

        // Gestion du bouton "Passer"
        skipBtn.addEventListener('click', () => {
            if (confirm('⚠️ Voulez-vous vraiment passer cette étape ? Vous pourrez créer votre compte plus tard.')) {
                closePopup();
                resolve({ skipped: true });
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
            const siret = siretInput ? siretInput.value.replace(/\s+/g, '') : null;

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

            // Valider le SIRET si présent
            if (siret && siret.length !== 14) {
                showError('Le numéro SIRET doit contenir exactement 14 chiffres');
                return;
            }

            try {
                submitBtn.disabled = true;
                submitBtn.textContent = '⏳ Création du compte...';
                errorDiv.style.display = 'none';

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
                        role: 'company',
                        company_id: companyId
                    })
                });

                const result = await response.json();

                if (result.success) {
                    console.log('✅ Compte entreprise créé avec succès:', result);
                    showSuccess('✅ Compte créé avec succès ! Redirection...');
                    
                    // Fermer la popup après 1 seconde
                    setTimeout(() => {
                        closePopup();
                        resolve(result);
                    }, 1000);
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
