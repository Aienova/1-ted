/**
 * Script pour la page d'inscription
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    const roleSelect = document.getElementById('role');
    const companyFields = document.getElementById('companyFields');

    // Afficher/masquer les champs entreprise selon le rôle
    roleSelect.addEventListener('change', function() {
        if (this.value === 'company') {
            companyFields.style.display = 'block';
        } else {
            companyFields.style.display = 'none';
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Masquer les messages
        errorMessage.style.display = 'none';
        successMessage.style.display = 'none';

        // Récupérer les données du formulaire
        const formData = {
            firstname: document.getElementById('firstname').value.trim(),
            lastname: document.getElementById('lastname').value.trim(),
            email: document.getElementById('email').value.trim(),
            login: document.getElementById('login').value.trim(),
            password: document.getElementById('password').value,
            telephone: document.getElementById('telephone').value.trim(),
            role: document.getElementById('role').value,
            country_id: document.getElementById('country_id').value || null,
            activity_id: document.getElementById('activity_id').value || null
        };

        // Ajouter les champs entreprise si nécessaire
        if (formData.role === 'company') {
            formData.company_name = document.getElementById('company_name').value.trim();
            formData.company_siret = document.getElementById('company_siret').value.trim();
        }

        const confirmPassword = document.getElementById('confirmPassword').value;

        // Validation côté client
        if (!formData.email || !formData.login || !formData.password) {
            errorMessage.textContent = 'Veuillez remplir tous les champs obligatoires';
            errorMessage.style.display = 'block';
            return;
        }

        // Validation format email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(formData.email)) {
            errorMessage.textContent = 'Format d\'email invalide';
            errorMessage.style.display = 'block';
            return;
        }

        // Validation longueur mot de passe
        if (formData.password.length < 8) {
            errorMessage.textContent = 'Le mot de passe doit contenir au moins 8 caractères';
            errorMessage.style.display = 'block';
            return;
        }

        // Vérifier la confirmation du mot de passe
        if (formData.password !== confirmPassword) {
            errorMessage.textContent = 'Les mots de passe ne correspondent pas';
            errorMessage.style.display = 'block';
            return;
        }

        try {
            // Désactiver le bouton pendant l'envoi
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Création en cours...';

            // Envoyer la requête
            const response = await fetch(`${baseUrl}/function/createAccount.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            console.log('📝 Résultat création compte:', result);

            if (result.success) {
                // Afficher immédiatement le message de succès
                successMessage.textContent = 'Compte créé avec succès ! Envoi de l\'email de vérification...';
                successMessage.style.display = 'block';
                
                // Envoyer l'email de vérification
                try {
                    const accountId = result.account_id || result.accountId;
                    console.log('📧 Envoi email de vérification pour account_id:', accountId);
                    
                    const verificationResponse = await fetch(`${baseUrl}/function/sendVerificationEmail.php`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            account_id: accountId,
                            lang: lang
                        })
                    });
                    
                    console.log('📬 Réponse serveur:', verificationResponse.status);
                    const verificationResult = await verificationResponse.json();
                    console.log('📬 Résultat vérification:', verificationResult);
                    
                    if (verificationResult.success) {
                        console.log('✅ Email de vérification envoyé avec succès');
                        successMessage.textContent = '✅ Compte créé avec succès ! Un email de vérification vous a été envoyé. Redirection dans 3 secondes...';
                    } else {
                        console.warn('⚠️ Erreur envoi email:', verificationResult.message);
                        successMessage.textContent = '✅ Compte créé ! Erreur lors de l\'envoi de l\'email. Contactez le support.';
                    }
                } catch (emailError) {
                    console.error('❌ Erreur email de vérification:', emailError);
                    successMessage.textContent = '✅ Compte créé ! Erreur lors de l\'envoi de l\'email. Contactez le support.';
                }

                // Rediriger vers la page de connexion après 3 secondes
                setTimeout(() => {
                    console.log('🔄 Redirection vers la page de connexion...');
                    window.location.href = `${baseUrl}/${lang}/user-account/connection`;
                }, 3000);
            } else {
                errorMessage.textContent = result.message || 'Erreur lors de la création du compte';
                errorMessage.style.display = 'block';

                // Réactiver le bouton
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        } catch (error) {
            console.error('Erreur:', error);
            errorMessage.textContent = 'Erreur de connexion au serveur';
            errorMessage.style.display = 'block';

            // Réactiver le bouton
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = false;
            submitButton.textContent = 'Créer mon compte';
        }
    });
});
