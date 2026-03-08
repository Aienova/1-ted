// Formulaire d'édition des informations de contact
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['job-contact'] = {
    generateForm: function(currentData) {
        // Vérifier si l'email est en lecture seule
        const emailElement = document.querySelector('[data-table="contact"][data-column="email"]');
        const isEmailReadonly = emailElement && emailElement.getAttribute('data-readonly') === 'true';
        
        return `
            <div class="form-group">
                <label for="contact-first-name-input">Prénom du contact</label>
                <input type="text" id="contact-first-name-input" class="form-control" value="${currentData.first_name || ''}" data-column="first_name" data-table="contact">
            </div>
            <div class="form-group">
                <label for="contact-last-name-input">Nom du contact</label>
                <input type="text" id="contact-last-name-input" class="form-control" value="${currentData.last_name || ''}" data-column="last_name" data-table="contact">
            </div>
            <div class="form-group">
                <label for="contact-email-input">Email ${isEmailReadonly ? '🔒' : ''}</label>
                <input type="email" id="contact-email-input" class="form-control" value="${currentData.email || ''}" data-column="email" data-table="contact" ${isEmailReadonly ? 'readonly style="background-color: #f3f4f6; cursor: not-allowed;"' : ''}>
                ${isEmailReadonly ? '<small style="color: #777;">L\'email ne peut pas être modifié car il est lié à votre compte.</small>' : ''}
            </div>
            <div class="form-group">
                <label for="contact-phone-input">Téléphone</label>
                <input type="tel" id="contact-phone-input" class="form-control" value="${currentData.phone || ''}" data-column="phone" data-table="contact">
            </div>
        `;
    },
    
    getData: function() {
        // Vérifier si l'email est en lecture seule
        const emailInput = document.getElementById('contact-email-input');
        const isEmailReadonly = emailInput && emailInput.hasAttribute('readonly');
        
        const data = {
            first_name: document.getElementById('contact-first-name-input').value,
            last_name: document.getElementById('contact-last-name-input').value,
            phone: document.getElementById('contact-phone-input').value
        };
        
        // N'inclure l'email que s'il n'est pas en lecture seule
        if (!isEmailReadonly) {
            data.email = emailInput.value;
        }
        
        return data;
    }
};
