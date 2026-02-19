// Formulaire d'édition des exigences du poste
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['job-requirements'] = {
    generateForm: function(currentData) {
        return `
            <div class="form-group">
                <label for="degree-input">Niveau d'études requis</label>
                <select id="degree-input" class="form-control" data-column="degree" data-table="requirement">
                    <option value="">-- Sélectionner --</option>
                    <option value="Sans diplôme" ${currentData.degree === 'Sans diplôme' ? 'selected' : ''}>Sans diplôme</option>
                    <option value="Bac" ${currentData.degree === 'Bac' ? 'selected' : ''}>Bac</option>
                    <option value="Bac+2" ${currentData.degree === 'Bac+2' ? 'selected' : ''}>Bac+2</option>
                    <option value="Bac+3" ${currentData.degree === 'Bac+3' ? 'selected' : ''}>Bac+3 (Licence)</option>
                    <option value="Bac+5" ${currentData.degree === 'Bac+5' ? 'selected' : ''}>Bac+5 (Master)</option>
                    <option value="Bac+8" ${currentData.degree === 'Bac+8' ? 'selected' : ''}>Bac+8 (Doctorat)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="experience-years-input">Années d'expérience requises</label>
                <input type="number" id="experience-years-input" class="form-control" value="${currentData.experience_years || ''}" data-column="experience_years" data-table="requirement" min="0" max="50">
            </div>
            <div class="form-group">
                <label for="requirement-description-input">Compétences et qualifications</label>
                <textarea id="requirement-description-input" class="form-control" rows="8" data-column="description" data-table="requirement">${currentData.description || ''}</textarea>
                <small class="form-text">Listez les compétences techniques, soft skills et qualifications nécessaires.</small>
            </div>
        `;
    },
    
    getData: function() {
        return {
            degree: document.getElementById('degree-input').value,
            experience_years: document.getElementById('experience-years-input').value,
            description: document.getElementById('requirement-description-input').value
        };
    }
};
