// Formulaire d'édition du titre et de l'entreprise
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['job-title'] = {
    generateForm: function(currentData) {
        return `
            <div class="form-group">
                <label for="job-title-input">Titre du poste</label>
                <input type="text" id="job-title-input" class="form-control" value="${currentData.title || ''}" data-column="title" data-table="job">
            </div>
            <div class="form-group">
                <label for="company-name-input">Nom de l'entreprise</label>
                <input type="text" id="company-name-input" class="form-control" value="${currentData.name || ''}" data-column="name" data-table="company">
            </div>
        `;
    },
    
    getData: function() {
        return {
            title: document.getElementById('job-title-input').value,
            name: document.getElementById('company-name-input').value
        };
    }
};
