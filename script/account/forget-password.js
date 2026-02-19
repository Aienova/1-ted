/**
 * Script pour la page mot de passe oublié
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgetPasswordForm');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Masquer les messages
        errorMessage.style.display = 'none';
        successMessage.style.display = 'none';

        // Récupérer l'email
        const email = document.getElementById('email').value.trim();

        // Validation côté client
        if (!email) {
            errorMessage.textContent = 'Veuillez entrer votre email';
            errorMessage.style.display = 'block';
            return;
        }

        // Validation format email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMessage.textContent = 'Format d\'email invalide';
            errorMessage.style.display = 'block';
            return;
        }

        try {
            // Désactiver le bouton pendant l'envoi
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Envoi en cours...';

            // Envoyer la requête
            const response = await fetch(`${baseUrl}/function/getAccountForgetCode.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email })
            });

            const result = await response.json();

            if (result.success) {
                successMessage.textContent = 'Un email de réinitialisation a été envoyé à votre adresse.';
                successMessage.style.display = 'block';
                form.reset();
            } else {
                errorMessage.textContent = result.message || 'Erreur lors de l\'envoi de l\'email';
                errorMessage.style.display = 'block';
            }

            // Réactiver le bouton
            submitButton.disabled = false;
            submitButton.textContent = originalText;

        } catch (error) {
            console.error('Erreur:', error);
            errorMessage.textContent = 'Erreur de connexion au serveur';
            errorMessage.style.display = 'block';

            // Réactiver le bouton
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = false;
            submitButton.textContent = 'Envoyer le lien';
        }
    });
});
