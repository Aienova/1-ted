// Formulaire d'édition de la description du poste
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['job-description'] = {
    generateForm: function(currentData) {
        return `
            <div class="form-group">
                <label for="description-input">Description du poste</label>
                <textarea id="description-input" class="form-control" rows="10" data-column="description" data-table="job">${currentData.description || ''}</textarea>
                <small class="form-text">Décrivez les missions principales, les responsabilités et les tâches quotidiennes du poste.</small>
            </div>
        `;
    },
    
    getData: function() {
        return {
            description: document.getElementById('description-input').value
        };
    }
};
