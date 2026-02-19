// Formulaire d'édition des informations de contact
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['job-contact'] = {
    generateForm: function(currentData) {
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
                <label for="contact-email-input">Email</label>
                <input type="email" id="contact-email-input" class="form-control" value="${currentData.email || ''}" data-column="email" data-table="contact">
            </div>
            <div class="form-group">
                <label for="contact-phone-input">Téléphone</label>
                <input type="tel" id="contact-phone-input" class="form-control" value="${currentData.phone || ''}" data-column="phone" data-table="contact">
            </div>
        `;
    },
    
    getData: function() {
        return {
            first_name: document.getElementById('contact-first-name-input').value,
            last_name: document.getElementById('contact-last-name-input').value,
            email: document.getElementById('contact-email-input').value,
            phone: document.getElementById('contact-phone-input').value
        };
    }
};
