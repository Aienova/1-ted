// Formulaire d'édition pour les compétences techniques
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['skills'] = {
    skills: [],
    editingIndex: -1, // -1 signifie qu'on n'est pas en mode édition
    
    generateForm: function(currentData) {
        return `
            <div class="form-group">
                <label>Compétences techniques</label>
                <div id="skills-list" class="skills-list-container"></div>
            </div>
            
            <div class="add-skill-form">
                <h3>Ajouter une compétence</h3>
                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="new-skill-title">Nom de la compétence</label>
                        <input 
                            type="text" 
                            id="new-skill-title" 
                            class="form-input" 
                            placeholder="Ex: JavaScript, Python, React..."
                        >
                    </div>
                    <div class="form-group" style="width: 150px;">
                        <label for="new-skill-years">Années d'exp.</label>
                        <input 
                            type="number" 
                            id="new-skill-years" 
                            class="form-input" 
                            placeholder="Ex: 3"
                            min="0"
                            max="15"
                        >
                    </div>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="new-skill-favorite">
                        <span>⭐ Compétence favorite (affichée en premier)</span>
                    </label>
                </div>
                <button type="button" class="editor-btn editor-btn-add" id="skill-submit-btn" onclick="window.FormEditors['skills'].addSkill()">
                    ➕ Ajouter la compétence
                </button>
                <button type="button" class="editor-btn editor-btn-cancel" id="skill-cancel-btn" style="display: none;" onclick="window.FormEditors['skills'].cancelEdit()">
                    ❌ Annuler
                </button>
            </div>
        `;
    },
    
    init: function(currentData) {
        this.skills = [];
        this.editingIndex = -1;
        
        // Récupérer les compétences actuelles depuis le DOM
        const parentElement = document.querySelector('[data-editor="skills"]');
        if (parentElement) {
            const skillItems = parentElement.querySelectorAll('li');
            skillItems.forEach(item => {
                const text = item.textContent.trim();
                const isFavorite = text.startsWith('⭐');
                
                // Extraire le titre et les années depuis les spans séparés
                const titleSpan = item.querySelector('[data-column="title"]');
                const yearSpan = item.querySelector('[data-column="year"]');
                
                if (titleSpan && yearSpan) {
                    const title = titleSpan.textContent.trim();
                    const years = parseInt(yearSpan.textContent.trim()) || 0;
                    const id = titleSpan.getAttribute('data-id') || null;
                    
                    this.skills.push({
                        id: id,
                        title: title,
                        years: years,
                        favorite: isFavorite
                    });
                } else if (titleSpan) {
                    // Compétence sans années spécifiées
                    const title = titleSpan.textContent.trim();
                    const id = titleSpan.getAttribute('data-id') || null;
                    this.skills.push({
                        id: id,
                        title: title,
                        years: 0,
                        favorite: isFavorite
                    });
                }
            });
        }
        
        this.renderSkills();
    },
    
    addSkill: function() {
        const title = document.getElementById('new-skill-title')?.value.trim();
        const years = parseInt(document.getElementById('new-skill-years')?.value) || 0;
        const favorite = document.getElementById('new-skill-favorite')?.checked || false;
        
        if (!title) {
            alert('Veuillez entrer le nom de la compétence');
            return;
        }
        
        if (this.editingIndex >= 0) {
            // Mode édition : mettre à jour la compétence existante en conservant l'ID
            const existingId = this.skills[this.editingIndex].id;
            this.skills[this.editingIndex] = {
                id: existingId,
                title: title,
                years: years,
                favorite: favorite
            };
            this.editingIndex = -1;
        } else {
            // Mode ajout : ajouter une nouvelle compétence sans ID (sera ajouté après insertion)
            this.skills.push({
                id: null,
                title: title,
                years: years,
                favorite: favorite
            });
        }
        
        // Réinitialiser le formulaire
        this.resetForm();
        this.renderSkills();
    },
    
    editSkill: function(index) {
        const skill = this.skills[index];
        if (!skill) return;
        
        // Remplir le formulaire avec les données de la compétence
        document.getElementById('new-skill-title').value = skill.title;
        document.getElementById('new-skill-years').value = skill.years;
        document.getElementById('new-skill-favorite').checked = skill.favorite;
        
        // Passer en mode édition
        this.editingIndex = index;
        
        // Changer le titre
        const formTitle = document.querySelector('.add-skill-form h3');
        if (formTitle) {
            formTitle.textContent = 'Modifier une compétence';
        }
        
        // Changer le bouton
        const submitBtn = document.getElementById('skill-submit-btn');
        const cancelBtn = document.getElementById('skill-cancel-btn');
        if (submitBtn) {
            submitBtn.innerHTML = '✏️ Mettre à jour';
            submitBtn.className = 'editor-btn editor-btn-upload'; // Couleur différente
        }
        if (cancelBtn) {
            cancelBtn.style.display = 'inline-block';
        }
        
        // Scroll vers le formulaire
        document.querySelector('.add-skill-form')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    },
    
    cancelEdit: function() {
        this.editingIndex = -1;
        this.resetForm();
    },
    
    resetForm: function() {
        document.getElementById('new-skill-title').value = '';
        document.getElementById('new-skill-years').value = '';
        document.getElementById('new-skill-favorite').checked = false;
        
        // Remettre le titre en mode ajout
        const formTitle = document.querySelector('.add-skill-form h3');
        if (formTitle) {
            formTitle.textContent = 'Ajouter une compétence';
        }
        
        // Remettre le bouton en mode ajout
        const submitBtn = document.getElementById('skill-submit-btn');
        const cancelBtn = document.getElementById('skill-cancel-btn');
        if (submitBtn) {
            submitBtn.innerHTML = '➕ Ajouter la compétence';
            submitBtn.className = 'editor-btn editor-btn-add';
        }
        if (cancelBtn) {
            cancelBtn.style.display = 'none';
        }
    },
    
    removeSkill: function(index) {
        this.skills.splice(index, 1);
        this.renderSkills();
    },
    
    toggleFavorite: function(index) {
        this.skills[index].favorite = !this.skills[index].favorite;
        this.renderSkills();
    },
    
    renderSkills: function() {
        const list = document.getElementById('skills-list');
        if (!list) return;
        
        // Trier: favoris en premier
        const sortedSkills = [...this.skills].sort((a, b) => {
            if (a.favorite && !b.favorite) return -1;
            if (!a.favorite && b.favorite) return 1;
            return 0;
        });
        
        list.innerHTML = sortedSkills.map((skill, originalIndex) => {
            const index = this.skills.indexOf(skill);
            const percentage = Math.min((skill.years / 15) * 100, 100);
            
            return `
                <div class="skill-item">
                    <div class="skill-header">
                        <span class="skill-title">
                            ${skill.favorite ? '⭐ ' : ''}${skill.title}
                        </span>
                        <span class="skill-years">${skill.years} an${skill.years > 1 ? 's' : ''}</span>
                    </div>
                    <div class="skill-progress-bar">
                        <div class="skill-progress-fill" style="width: ${percentage}%"></div>
                    </div>
                    <div class="skill-actions">
                        <button type="button" class="skill-action-btn" onclick="window.FormEditors['skills'].toggleFavorite(${index})" title="Marquer comme favori">
                            ${skill.favorite ? '⭐' : '☆'}
                        </button>
                        <button type="button" class="skill-action-btn edit" onclick="window.FormEditors['skills'].editSkill(${index})" title="Modifier">
                            ✏️
                        </button>
                        <button type="button" class="skill-action-btn remove" onclick="window.FormEditors['skills'].removeSkill(${index})" title="Supprimer">
                            🗑️
                        </button>
                    </div>
                </div>
            `;
        }).join('') || '<p class="empty-message">Aucune compétence ajoutée</p>';
    },
    
    getData: function() {
        // Retourner les compétences au format attendu par le backend
        return {
            'skills': this.skills
        };
    },
    
    updateSkillId: function(index, newId) {
        if (this.skills[index]) {
            this.skills[index].id = newId;
        }
    }
};
