// Formulaire d'édition pour la description
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['description'] = {
    
    generateForm: function(currentData) {
        // Récupérer la description actuelle
        const parentElement = document.querySelector('[data-editor="description"]');
        let description = '';
        
        if (parentElement) {
            description = parentElement.querySelector('p')?.textContent.trim() || '';
        }
        
        return `
            <div class="form-group">
                <label for="edit-description">Description professionnelle</label>
                <textarea 
                    id="edit-description" 
                    class="form-textarea" 
                    rows="8"
                    placeholder="Décrivez votre profil professionnel, vos objectifs de carrière, vos points forts..."
                >${description}</textarea>
                <small class="form-help">Présentez-vous de manière concise et professionnelle</small>
            </div>
            
            <div class="form-group">
                <label>Suggestions de contenu :</label>
                <div class="suggestions-list">
                    <button type="button" class="suggestion-btn" onclick="window.FormEditors['description'].addSuggestion('Professionnel(le) passionné(e) et motivé(e)')">
                        💡 Professionnel(le) passionné(e) et motivé(e)
                    </button>
                    <button type="button" class="suggestion-btn" onclick="window.FormEditors['description'].addSuggestion('Fort(e) de X années d\\'expérience')">
                        💡 Fort(e) de X années d'expérience
                    </button>
                    <button type="button" class="suggestion-btn" onclick="window.FormEditors['description'].addSuggestion('Recherche de nouveaux défis professionnels')">
                        💡 Recherche de nouveaux défis
                    </button>
                </div>
            </div>
        `;
    },
    
    init: function(currentData) {
        // Rien de spécial à initialiser
    },
    
    addSuggestion: function(text) {
        const textarea = document.getElementById('edit-description');
        if (textarea) {
            const currentValue = textarea.value.trim();
            if (currentValue) {
                textarea.value = currentValue + ' ' + text;
            } else {
                textarea.value = text;
            }
            textarea.focus();
        }
    },
    
    getData: function() {
        return {
            'description': document.getElementById('edit-description')?.value || ''
        };
    }
};
