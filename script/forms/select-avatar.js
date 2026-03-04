/**
 * Script pour sélectionner et assigner un avatar à un candidat
 */

let currentCandidateId = null;
let selectedAvatarUrl = null;

/**
 * Ouvre la popup de sélection d'avatar
 */
function openAvatarSelector(candidateId, currentAvatarUrl = null) {
    currentCandidateId = candidateId;
    selectedAvatarUrl = currentAvatarUrl;
    
    const popup = document.getElementById('avatar-selector-popup');
    if (!popup) {
        console.error('Popup avatar-selector-popup introuvable');
        return;
    }
    
    // Réinitialiser la sélection de genre
    const maleRadio = document.getElementById('gender-male');
    const femaleRadio = document.getElementById('gender-female');
    
    if (maleRadio) maleRadio.checked = false;
    if (femaleRadio) femaleRadio.checked = false;
    
    // Afficher l'aperçu avec l'avatar actuel ou par défaut
    updatePreview(currentAvatarUrl || '/media/image/icon/avatar/default.png');
    
    // Vider la galerie
    const gallery = document.getElementById('avatar-gallery');
    if (gallery) {
        gallery.innerHTML = '<p style="text-align: center; color: #999; padding: 40px;">Sélectionnez un genre pour afficher les avatars</p>';
    }
    
    // Désactiver le bouton de sauvegarde
    const saveBtn = document.getElementById('save-avatar-btn');
    if (saveBtn) {
        saveBtn.disabled = !currentAvatarUrl;
    }
    
    // Afficher la popup
    popup.classList.add('active');
}

/**
 * Ferme la popup de sélection d'avatar
 */
function closeAvatarSelector() {
    const popup = document.getElementById('avatar-selector-popup');
    if (popup) {
        popup.classList.remove('active');
    }
    
    currentCandidateId = null;
    selectedAvatarUrl = null;
}

/**
 * Charge les avatars en fonction du genre sélectionné
 */
async function loadAvatarsByGender(gender) {
    const gallery = document.getElementById('avatar-gallery');
    if (!gallery) return;
    
    // Afficher un loader
    gallery.innerHTML = '<div style="text-align: center; padding: 40px;"><div class="spinner"></div><p>Chargement des avatars...</p></div>';
    
    try {
        // Récupérer la liste des avatars depuis le dossier
        const response = await fetch(`/function/getAvatarsList.php?gender=${gender}`);
        const data = await response.json();
        
        if (!data.success || !data.avatars || data.avatars.length === 0) {
            gallery.innerHTML = '<p style="text-align: center; color: #999; padding: 40px;">Aucun avatar disponible</p>';
            return;
        }
        
        // Afficher la galerie d'avatars
        gallery.innerHTML = data.avatars.map((avatar, index) => `
            <div class="avatar-option" onclick="selectAvatar('${avatar}')">
                <input type="radio" 
                       name="avatar-choice" 
                       id="avatar-${index}" 
                       value="${avatar}"
                       ${selectedAvatarUrl === avatar ? 'checked' : ''}>
                <label for="avatar-${index}">
                    <img src="${avatar}" alt="Avatar ${index + 1}">
                </label>
            </div>
        `).join('');
        
    } catch (error) {
        console.error('Erreur lors du chargement des avatars:', error);
        gallery.innerHTML = '<p style="text-align: center; color: #e74c3c; padding: 40px;">⚠️ Erreur lors du chargement des avatars</p>';
    }
}

/**
 * Sélectionne un avatar
 */
function selectAvatar(avatarUrl) {
    selectedAvatarUrl = avatarUrl;
    
    // Mettre à jour l'aperçu
    updatePreview(avatarUrl);
    
    // Activer le bouton de sauvegarde
    const saveBtn = document.getElementById('save-avatar-btn');
    if (saveBtn) {
        saveBtn.disabled = false;
    }
}

/**
 * Met à jour l'aperçu de l'avatar
 */
function updatePreview(avatarUrl) {
    const preview = document.getElementById('avatar-preview');
    if (preview) {
        preview.src = avatarUrl;
    }
}

/**
 * Sauvegarde l'avatar sélectionné
 */
async function saveAvatar() {
    if (!currentCandidateId || !selectedAvatarUrl) {
        alert('Veuillez sélectionner un avatar');
        return;
    }
    
    const saveBtn = document.getElementById('save-avatar-btn');
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.textContent = '⏳ Sauvegarde...';
    }
    
    try {
        const response = await fetch('/function/assignAvatar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                candidateId: currentCandidateId,
                avatarUrl: selectedAvatarUrl
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✅ Avatar assigné avec succès !');
            closeAvatarSelector();
            
            // Recharger la liste des candidats
            if (typeof loadCandidates === 'function') {
                loadCandidates();
            } else {
                window.location.reload();
            }
        } else {
            alert('❌ Erreur: ' + (data.error || 'Erreur inconnue'));
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.textContent = '💾 Sauvegarder';
            }
        }
        
    } catch (error) {
        console.error('Erreur:', error);
        alert('❌ Erreur lors de la sauvegarde de l\'avatar');
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.textContent = '💾 Sauvegarder';
        }
    }
}

// Event listeners pour les boutons de genre
document.addEventListener('DOMContentLoaded', () => {
    const maleRadio = document.getElementById('gender-male');
    const femaleRadio = document.getElementById('gender-female');
    
    if (maleRadio) {
        maleRadio.addEventListener('change', (e) => {
            if (e.target.checked) {
                loadAvatarsByGender('men');
            }
        });
    }
    
    if (femaleRadio) {
        femaleRadio.addEventListener('change', (e) => {
            if (e.target.checked) {
                loadAvatarsByGender('women');
            }
        });
    }
    
    // Fermer la popup en cliquant sur l'overlay
    const popup = document.getElementById('avatar-selector-popup');
    if (popup) {
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                closeAvatarSelector();
            }
        });
    }
});
