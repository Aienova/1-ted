/**
 * Script pour la page d'inscription
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    const roleSelect = document.getElementById('role');
    const recruiterFields = document.getElementById('recruiterFields');

    // Afficher/masquer les champs recruteur selon le rôle
    roleSelect.addEventListener('change', function() {
        if (this.value === 'recruiter') {
            recruiterFields.style.display = 'block';
        } else {
            recruiterFields.style.display = 'none';
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

        // Ajouter les champs recruteur si nécessaire
        if (formData.role === 'recruiter') {
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

            if (result.success) {
                successMessage.textContent = 'Compte créé avec succès ! Redirection vers la connexion...';
                successMessage.style.display = 'block';

                // Rediriger vers la page de connexion
                setTimeout(() => {
                    window.location.href = `${baseUrl}/${lang}/user-account/connection`;
                }, 2000);
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
