/**
 * Script pour la page nouveau mot de passe
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newPasswordForm');
    
    // Vérifier si le formulaire existe (il peut ne pas exister si le code est invalide)
    if (!form) {
        return;
    }

    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Masquer les messages
        errorMessage.style.display = 'none';
        successMessage.style.display = 'none';

        // Récupérer les données du formulaire
        const forgetCode = document.getElementById('forgetCode').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        // Validation côté client
        if (!newPassword || !confirmPassword) {
            errorMessage.textContent = 'Veuillez remplir tous les champs';
            errorMessage.style.display = 'block';
            return;
        }

        if (newPassword.length < 8) {
            errorMessage.textContent = 'Le mot de passe doit contenir au moins 8 caractères';
            errorMessage.style.display = 'block';
            return;
        }

        if (newPassword !== confirmPassword) {
            errorMessage.textContent = 'Les mots de passe ne correspondent pas';
            errorMessage.style.display = 'block';
            return;
        }

        try {
            // Désactiver le bouton pendant l'envoi
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Modification en cours...';

            // Envoyer la requête
            const response = await fetch(`${baseUrl}/function/changePassword.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'reset',
                    forgetCode,
                    newPassword
                })
            });

            const result = await response.json();

            if (result.success) {
                successMessage.textContent = 'Mot de passe modifié avec succès ! Redirection...';
                successMessage.style.display = 'block';

                // Rediriger vers la page de connexion
                setTimeout(() => {
                    window.location.href = `${baseUrl}/${lang}/user-account/connection`;
                }, 2000);
            } else {
                errorMessage.textContent = result.message || 'Erreur lors du changement de mot de passe';
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
            submitButton.textContent = 'Confirmer';
        }
    });
});
