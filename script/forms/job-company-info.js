// Formulaire d'édition des informations sur l'entreprise
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['job-company-info'] = {
    generateForm: function(currentData) {
        return `
            <div class="form-group">
                <label for="company-name-input">Nom de l'entreprise</label>
                <input type="text" id="company-name-input" class="form-control" value="${currentData.name || ''}" data-column="name" data-table="company">
            </div>
            <div class="form-group">
                <label for="domain-input">Secteur d'activité</label>
                <input type="text" id="domain-input" class="form-control" value="${currentData.domain || ''}" data-column="domain" data-table="company" placeholder="Ex: Informatique, BTP, Commerce...">
            </div>
            <div class="form-group">
                <label for="capacity-input">Effectif (nombre d'employés)</label>
                <input type="number" id="capacity-input" class="form-control" value="${currentData.capacity || ''}" data-column="capacity" data-table="company" min="1">
            </div>
            <div class="form-group">
                <label for="hq-location-input">Localisation du siège social</label>
                <input type="text" id="hq-location-input" class="form-control" value="${currentData.hq_location || ''}" data-column="hq_location" data-table="company">
            </div>
            <div class="form-group">
                <label for="company-description-input">Description de l'entreprise</label>
                <textarea id="company-description-input" class="form-control" rows="6" data-column="description" data-table="company">${currentData.description || ''}</textarea>
                <small class="form-text">Présentez l'entreprise, ses valeurs, son histoire, etc.</small>
            </div>
        `;
    },
    
    getData: function() {
        return {
            name: document.getElementById('company-name-input').value,
            domain: document.getElementById('domain-input').value,
            capacity: document.getElementById('capacity-input').value,
            hq_location: document.getElementById('hq-location-input').value,
            description: document.getElementById('company-description-input').value
        };
    }
};
