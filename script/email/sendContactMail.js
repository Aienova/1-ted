/**
 * Gestion dynamique du formulaire de contact
 * - Envoi AJAX sans rechargement de page
 * - Affichage du loader (aienova-loader.gif) pendant l'envoi
 * - Affichage du message de confirmation ou d'erreur à la fin
 */

(function () {
    const form      = document.getElementById('contactForm');
    const loader    = document.getElementById('contactLoader');
    const success   = document.getElementById('contactSuccess');
    const errorBox  = document.getElementById('contactError');

    if (!form) return;

    const humanCheck  = document.getElementById('humanCheck');
    const submitBtn   = document.getElementById('contactSubmit');

    // Activer le bouton uniquement si le captcha est coché
    if (humanCheck && submitBtn) {
        humanCheck.addEventListener('change', function () {
            submitBtn.disabled = !this.checked;
        });
    }

    let isSending = false; // Garde anti-double-submit

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Vérification captcha côté client
        if (!humanCheck || !humanCheck.checked) {
            errorBox.textContent   = 'Veuillez confirmer que vous n\'êtes pas un robot.';
            errorBox.style.display = 'block';
            return;
        }

        if (isSending) return; // Ignorer les clics multiples
        isSending = true;

        // Réinitialiser l'état
        errorBox.style.display = 'none';
        errorBox.textContent   = '';

        // Afficher le loader, cacher le formulaire
        form.style.display   = 'none';
        loader.style.display = 'block';

        try {
            const formData = new FormData(form);
            const endpoint = getFunctionUrl('sendContactMail.php');

            const response = await fetch(endpoint, {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            // Cacher le loader
            loader.style.display = 'none';

            if (data.success) {
                // Afficher le message de succès
                success.style.display = 'block';
            } else {
                // Réafficher le formulaire avec le message d'erreur
                errorBox.textContent   = data.message;
                errorBox.style.display = 'block';
                form.style.display     = 'block';
                isSending = false; // Réautoriser un nouvel envoi
            }
        } catch (err) {
            loader.style.display   = 'none';
            errorBox.textContent   = "Une erreur réseau est survenue. Veuillez vérifier votre connexion et réessayer.";
            errorBox.style.display = 'block';
            form.style.display     = 'block';
            isSending = false; // Réautoriser un nouvel envoi
        }
    });
})();
