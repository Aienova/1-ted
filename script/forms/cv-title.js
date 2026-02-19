// Formulaire d'édition pour le titre du CV et photo de profil
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['cv-title'] = {
    currentPhotoUrl: '',
    pendingPhotoData: null, // Stockage temporaire de la photo en base64
    
    generateForm: function(currentData) {
        // Récupérer la photo actuelle depuis le DOM
        const profilePhoto = document.querySelector('.profile-photo');
        const bgImage = profilePhoto?.style.backgroundImage;
        if (bgImage && bgImage !== 'none') {
            const urlMatch = bgImage.match(/url\(['"]?(.+?)['"]?\)/);
            if (urlMatch) {
                this.currentPhotoUrl = urlMatch[1];
            }
        }
        
        return `
            <div class="form-group">
                <label for="edit-first-name">Prénom</label>
                <input 
                    type="text" 
                    id="edit-first-name" 
                    class="form-input" 
                    value="${currentData['first_name'] || ''}"
                    placeholder="Entrez le prénom"
                >
            </div>
            
            <div class="form-group">
                <label for="edit-last-name">Nom</label>
                <input 
                    type="text" 
                    id="edit-last-name" 
                    class="form-input" 
                    value="${currentData['last_name'] || ''}"
                    placeholder="Entrez le nom"
                >
            </div>
            
            <div class="form-group">
                <label for="edit-job-title">Titre du poste</label>
                <input 
                    type="text" 
                    id="edit-job-title" 
                    class="form-input" 
                    value="${currentData['job_title'] || ''}"
                    placeholder="Ex: Développeur Full Stack"
                >
            </div>
            
            <div class="form-group">
                <label for="edit-profile-photo">Photo de profil</label>
                <div class="photo-upload-container">
                    <div class="photo-preview" id="photo-preview">
                        ${this.currentPhotoUrl ? `<img src="${this.currentPhotoUrl}" alt="Photo de profil">` : '<div class="photo-placeholder">Aucune photo</div>'}
                    </div>
                    <div class="photo-upload-actions">
                        <input 
                            type="file" 
                            id="edit-profile-photo" 
                            class="form-input-file" 
                            accept="image/*"
                            style="display: none;"
                        >
                        <button type="button" class="editor-btn editor-btn-upload" onclick="document.getElementById('edit-profile-photo').click()">
                            📁 Choisir une photo
                        </button>
                        <button type="button" class="editor-btn editor-btn-remove" onclick="window.FormEditors['cv-title'].removePhoto()">
                            🗑️ Supprimer
                        </button>
                    </div>
                    <small class="form-help">Formats acceptés: JPG, PNG, GIF (max 5Mo)</small>
                </div>
            </div>
        `;
    },
    
    init: function(currentData) {
        // Gérer l'aperçu de la photo lors de la sélection
        const photoInput = document.getElementById('edit-profile-photo');
        if (photoInput) {
            photoInput.addEventListener('change', (e) => {
                this.handlePhotoUpload(e);
            });
        }
    },
    
    handlePhotoUpload: function(event) {
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
            this.pendingPhotoData = e.target.result; // Stocker en base64
            this.currentPhotoUrl = e.target.result;
            
            // Mettre à jour l'aperçu dans le formulaire
            const preview = document.getElementById('photo-preview');
            if (preview) {
                preview.innerHTML = `<img src="${this.currentPhotoUrl}" alt="Photo de profil">`;
            }
            
            // Mettre à jour immédiatement la photo dans le DOM principal
            const profilePhoto = document.querySelector('.profile-photo');
            if (profilePhoto) {
                profilePhoto.style.backgroundImage = `url('${e.target.result}')`;
                profilePhoto.style.backgroundSize = 'cover';
                console.log('📷 Photo mise à jour dans le DOM');
            }
            
            console.log('📷 Photo chargée en mémoire, sera uploadée lors de la sauvegarde');
        };
        reader.readAsDataURL(file);
    },
    
    uploadPhotoToServer: async function(mediaCode) {
        if (!this.pendingPhotoData) {
            console.log('Aucune photo en attente d\'upload');
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
                    type: 'photo',
                    imageData: this.pendingPhotoData
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                console.log('✅ Photo uploadée:', result.url);
                this.pendingPhotoData = null; // Nettoyer après upload
                return result.url;
            } else {
                console.error('❌ Erreur upload photo:', result.error);
                alert('Erreur lors de l\'upload de la photo: ' + result.error);
                return null;
            }
        } catch (error) {
        this.pendingPhotoData = null;
            console.error('❌ Erreur réseau upload photo:', error);
            alert('Erreur lors de l\'upload de la photo');
            return null;
        }
    },
    
    removePhoto: function() {
        this.currentPhotoUrl = '';
        this.pendingPhotoData = null;
        
        // Mettre à jour l'aperçu dans le formulaire
        const preview = document.getElementById('photo-preview');
        if (preview) {
            preview.innerHTML = '<div class="photo-placeholder">Aucune photo</div>';
        }
        
        // Supprimer la photo du DOM principal
        const profilePhoto = document.querySelector('.profile-photo');
        if (profilePhoto) {
            profilePhoto.style.backgroundImage = 'none';
            console.log('🗑️ Photo supprimée du DOM');
        }
        
        const photoInput = document.getElementById('edit-profile-photo');
        if (photoInput) {
            photoInput.value = '';
        }
    },
    
    getData: function() {
        return {
            'first_name': document.getElementById('edit-first-name')?.value || '',
            'last_name': document.getElementById('edit-last-name')?.value || '',
            'job_title': document.getElementById('edit-job-title')?.value || '',
            'profile-photo': this.currentPhotoUrl
        };
    }
};
