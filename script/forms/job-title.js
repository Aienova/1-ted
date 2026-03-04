// Formulaire d'édition du titre et de l'entreprise
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['job-title'] = {
    currentLogoUrl: '',
    pendingLogoData: null, // Stockage temporaire du logo en base64
    
    generateForm: function(currentData) {
        // Récupérer le logo actuel depuis le DOM
        const logoCircle = document.querySelector('.company-logo-circle');
        const bgImage = logoCircle?.style.backgroundImage;
        if (bgImage && bgImage !== 'none') {
            const urlMatch = bgImage.match(/url\(['"]?(.+?)['"]?\)/);
            if (urlMatch) {
                this.currentLogoUrl = urlMatch[1];
            }
        }
        
        return `
            <div class="form-group">
                <label for="edit-job-title">Titre du poste</label>
                <input 
                    type="text" 
                    id="edit-job-title" 
                    class="form-input" 
                    value="${currentData['title'] || ''}"
                    placeholder="Ex: Développeur Full Stack"
                >
            </div>
            
            <div class="form-group">
                <label for="edit-company-name">Nom de l'entreprise</label>
                <input 
                    type="text" 
                    id="edit-company-name" 
                    class="form-input" 
                    value="${currentData['name'] || ''}"
                    placeholder="Nom de l'entreprise"
                >
            </div>
            
            <div class="form-group">
                <label for="edit-company-logo">Logo de l'entreprise</label>
                <div class="photo-upload-container">
                    <div class="photo-preview" id="logo-preview" style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 2px solid #ddd; display: flex; align-items: center; justify-content: center; background: #f5f5f5;">
                        ${this.currentLogoUrl ? `<img src="${this.currentLogoUrl}" alt="Logo de l'entreprise" style="width: 100%; height: 100%; object-fit: cover;">` : '<div class="photo-placeholder" style="font-size: 48px;">🏢</div>'}
                    </div>
                    <div class="photo-upload-actions" style="margin-top: 10px;">
                        <input 
                            type="file" 
                            id="edit-company-logo" 
                            class="form-input-file" 
                            accept="image/*"
                            style="display: none;"
                        >
                        <button type="button" class="editor-btn editor-btn-upload" onclick="document.getElementById('edit-company-logo').click()">
                            📁 Choisir un logo
                        </button>
                        <button type="button" class="editor-btn editor-btn-remove" onclick="window.FormEditors['job-title'].removeLogo()">
                            🗑️ Supprimer
                        </button>
                    </div>
                    <small class="form-help">Formats acceptés: JPG, PNG, GIF (max 5Mo)</small>
                </div>
            </div>
        `;
    },
    
    init: function(currentData) {
        // Gérer l'aperçu du logo lors de la sélection
        const logoInput = document.getElementById('edit-company-logo');
        if (logoInput) {
            logoInput.addEventListener('change', (e) => {
                this.handleLogoUpload(e);
            });
        }
    },
    
    handleLogoUpload: function(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        // Vérifier le type de fichier
        if (!file.type.match('image.*')) {
            alert('Veuillez sélectionner une image valide');
            return;
        }
        
        // Vérifier la taille (5Mo max)
        if (file.size > 5 * 1024 * 1024) {
            alert('La taille de l\'image ne doit pas dépasser 5Mo');
            return;
        }
        
        // Créer un aperçu et stocker en base64
        const reader = new FileReader();
        reader.onload = (e) => {
            this.pendingLogoData = e.target.result; // Stocker en base64
            this.currentLogoUrl = e.target.result;
            
            // Mettre à jour l'aperçu dans le formulaire
            const preview = document.getElementById('logo-preview');
            if (preview) {
                preview.innerHTML = `<img src="${this.currentLogoUrl}" alt="Logo de l'entreprise" style="width: 100%; height: 100%; object-fit: cover;">`;
            }
            
            // Mettre à jour immédiatement le logo dans le DOM principal
            const logoCircle = document.querySelector('.company-logo-circle');
            if (logoCircle) {
                logoCircle.style.backgroundImage = `url('${e.target.result}')`;
                logoCircle.style.backgroundSize = 'cover';
                logoCircle.style.backgroundPosition = 'center';
                console.log('🏢 Logo mis à jour dans le DOM');
            }
            
            console.log('🏢 Logo chargé en mémoire, sera uploadé lors de la sauvegarde');
        };
        reader.readAsDataURL(file);
    },
    
    uploadLogoToServer: async function(mediaCode) {
        if (!this.pendingLogoData) {
            console.log('Aucun logo en attente d\'upload');
            return null;
        }
        
        try {
            const response = await fetch(getFunctionUrl('uploadImage.php'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    mediaCode: mediaCode,
                    type: 'logo',
                    imageData: this.pendingLogoData,
                    entityType: 'company'
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                console.log('✅ Logo uploadé:', result.url);
                this.pendingLogoData = null; // Nettoyer après upload
                return result.url;
            } else {
                console.error('❌ Erreur upload logo:', result.error);
                alert('Erreur lors de l\'upload du logo: ' + result.error);
                return null;
            }
        } catch (error) {
            this.pendingLogoData = null;
            console.error('❌ Erreur réseau upload logo:', error);
            alert('Erreur lors de l\'upload du logo: ' + error.message);
            return null;
        }
    },
    
    removeLogo: function() {
        this.currentLogoUrl = '';
        this.pendingLogoData = null;
        
        // Mettre à jour l'aperçu dans le formulaire
        const preview = document.getElementById('logo-preview');
        if (preview) {
            preview.innerHTML = '<div class="photo-placeholder" style="font-size: 48px;">🏢</div>';
        }
        
        // Supprimer le logo du DOM principal
        const logoCircle = document.querySelector('.company-logo-circle');
        if (logoCircle) {
            logoCircle.style.backgroundImage = 'none';
            console.log('🗑️ Logo supprimé du DOM');
        }
        
        const logoInput = document.getElementById('edit-company-logo');
        if (logoInput) {
            logoInput.value = '';
        }
    },
    
    getData: function() {
        return {
            'title': document.getElementById('edit-job-title')?.value || '',
            'name': document.getElementById('edit-company-name')?.value || '',
            'logoData': this.pendingLogoData // Retourner les données base64
        };
    }
};
