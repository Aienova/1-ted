/**
 * Script pour la page de connexion
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Masquer les messages
        errorMessage.style.display = 'none';
        successMessage.style.display = 'none';

        // Récupérer les données du formulaire
        const formData = {
            action: 'login',
            login: document.getElementById('login').value.trim(),
            password: document.getElementById('password').value
        };

        // Validation côté client
        if (!formData.login || !formData.password) {
            errorMessage.textContent = 'Veuillez remplir tous les champs';
            errorMessage.style.display = 'block';
            return;
        }

        try {
            // Envoyer la requête
            const response = await fetch(`${baseUrl}/function/connectionToAccount.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                successMessage.textContent = 'Connexion réussie ! Redirection...';
                successMessage.style.display = 'block';

                // Stocker le token dans localStorage
                localStorage.setItem('authToken', result.token);
                localStorage.setItem('accountId', result.accountId);
                localStorage.setItem('role', result.role);

                // Rediriger vers le dashboard selon le rôle
                setTimeout(() => {
                    window.location.href = `${baseUrl}/${lang}/user-account/${result.role}/dashboard`;
                }, 1500);
            } else {
                errorMessage.textContent = result.message || 'Erreur lors de la connexion';
                errorMessage.style.display = 'block';
            }
        } catch (error) {
            console.error('Erreur:', error);
            errorMessage.textContent = 'Erreur de connexion au serveur';
            errorMessage.style.display = 'block';
        }
    });
});