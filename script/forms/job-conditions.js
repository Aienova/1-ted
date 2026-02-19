// Formulaire d'édition des conditions de travail
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['job-conditions'] = {
    generateForm: function(currentData) {
        return `
            <div class="form-group">
                <label for="work-condition-input">Conditions de travail</label>
                <textarea id="work-condition-input" class="form-control" rows="6" data-column="work_condition" data-table="job">${currentData.work_condition || ''}</textarea>
                <small class="form-text">Indiquez les horaires, le télétravail, l'environnement de travail, etc.</small>
            </div>
        `;
    },
    
    getData: function() {
        return {
            work_condition: document.getElementById('work-condition-input').value
        };
    }
};
