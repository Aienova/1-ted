// Formulaire d'édition pour le QR Code et footer
if (typeof window.FormEditors === 'undefined') {
    window.FormEditors = {};
}

window.FormEditors['qrcode'] = {
    qrCodeUrl: '',
    pendingQRCodeData: null, // Stockage temporaire du QR code en base64
    
    generateForm: function(currentData) {
        return `
            <div class="form-group">
                <label for="edit-footer-text">Texte du footer</label>
                <textarea 
                    id="edit-footer-text" 
                    class="form-textarea" 
                    rows="3"
                    placeholder="Ex: Pour plus d'informations sur ce profil..."
                ></textarea>
            </div>
            
            <div class="form-group">
                <label for="edit-qrcode">QR Code</label>
                <div class="photo-upload-container">
                    <div class="qrcode-preview" id="qrcode-preview">
                        <div class="photo-placeholder">Aucun QR Code</div>
                    </div>
                    <div class="photo-upload-actions">
                        <input 
                            type="file" 
                            id="edit-qrcode" 
                            class="form-input-file" 
                            accept="image/*"
                            style="display: none;"
                        >
                        <button type="button" class="editor-btn editor-btn-upload" onclick="document.getElementById('edit-qrcode').click()">
                            📁 Choisir un QR Code
                        </button>
                        <button type="button" class="editor-btn editor-btn-remove" onclick="window.FormEditors['qrcode'].removeQRCode()">
                            🗑️ Supprimer
                        </button>
                    </div>
                    <small class="form-help">Vous pouvez générer un QR Code sur <a href="https://www.qr-code-generator.com/" target="_blank">qr-code-generator.com</a></small>
                </div>
            </div>
            
            <div class="form-group">
                <label for="edit-qr-url">URL du profil (optionnel)</label>
                <input 
                    type="url" 
                    id="edit-qr-url" 
                    class="form-input" 
                    placeholder="Ex: https://linkedin.com/in/votre-profil"
                >
                <small class="form-help">URL vers votre portfolio, LinkedIn, ou site web personnel</small>
            </div>
        `;
    },
    
    init: function(currentData) {
        this.qrCodeUrl = '';
        
        // Récupérer le texte du footer
        const footerText = document.querySelector('.footer-text p')?.textContent.trim();
        if (footerText) {
            const textarea = document.getElementById('edit-footer-text');
            if (textarea) textarea.value = footerText;
        }
        
        // Récupérer le QR code actuel
        const qrImg = document.querySelector('.footer-qr img');
        if (qrImg) {
            this.qrCodeUrl = qrImg.src;
            const qrPreview = document.getElementById('qrcode-preview');
            if (qrPreview) {
                qrPreview.innerHTML = `<img src="${this.qrCodeUrl}" alt="QR Code">`;
            }
        }
        
        // Gérer l'upload du QR code
        const qrInput = document.getElementById('edit-qrcode');
        if (qrInput) {
            qrInput.addEventListener('change', (e) => this.handleQRCodeUpload(e));
        }
    },
    
    handleQRCodeUpload: function(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        if (!file.type.match('image.*')) {
            alert('Veuillez sélectionner une image valide');
            return;
        }
        
        // Vérifier la taille (5Mo max)
        if (file.size > 5 * 1024 * 1024) {
            alert('La taille de l\'image ne doit pas dépasser 5Mo');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = (e) => {
            this.pendingQRCodeData = e.target.result; // Stocker en base64
            this.qrCodeUrl = e.target.result;
            
            // Mettre à jour la prévisualisation dans le popup
            const preview = document.getElementById('qrcode-preview');
            if (preview) {
                preview.innerHTML = `<img src="${this.qrCodeUrl}" alt="QR Code">`;
            }
            
            // Mettre à jour immédiatement le QR code dans le DOM principal
            const qrImg = document.querySelector('.footer-qr img');
            if (qrImg) {
                qrImg.src = this.qrCodeUrl;
                console.log('📷 QR Code mis à jour dans le DOM');
            }
            
            console.log('📷 QR Code chargé en mémoire, sera uploadé lors de la sauvegarde');
        };
        reader.readAsDataURL(file);
    },
    
    uploadQRCodeToServer: async function(mediaCode) {
        if (!this.pendingQRCodeData) {
            console.log('Aucun QR Code en attente d\'upload');
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
                    type: 'qrcode',
                    imageData: this.pendingQRCodeData
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                console.log('✅ QR Code uploadé:', result.url);
                this.pendingQRCodeData = null; // Nettoyer après upload
                return result.url;
            } else {
                console.error('❌ Erreur upload QR Code:', result.error);
                alert('Erreur lors de l\'upload du QR Code: ' + result.error);
                return null;
            }
        } catch (error) {
            console.error('❌ Erreur réseau upload QR Code:', error);
            alert('Erreur lors de l\'upload du QR Code');
            return null;
        }
    },
    
    removeQRCode: function() {
        this.qrCodeUrl = '';
        this.pendingQRCodeData = null;
        
        // Mettre à jour la prévisualisation dans le popup
        const preview = document.getElementById('qrcode-preview');
        if (preview) {
            preview.innerHTML = '<div class="photo-placeholder">Aucun QR Code</div>';
        }
        
        // Mettre à jour le QR code dans le DOM principal (remettre l'image par défaut)
        const qrImg = document.querySelector('.footer-qr img');
        if (qrImg) {
            qrImg.src = getAbsolutePath('media/image/qrcode.png');
            console.log('🗑️ QR Code supprimé du DOM');
        }
        
        const input = document.getElementById('edit-qrcode');
        if (input) input.value = '';
    },
    
    getData: function() {
        return {
            'footer-text': document.getElementById('edit-footer-text')?.value || '',
            'qrcode-url': this.qrCodeUrl,
            'profile-url': document.getElementById('edit-qr-url')?.value || ''
        };
    }
};
