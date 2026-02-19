// Gestion des onglets
document.addEventListener('DOMContentLoaded', () => {
    // Gérer les clics sur les onglets
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabName = button.getAttribute('data-tab');
            
            // Désactiver tous les onglets
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Activer l'onglet sélectionné
            button.classList.add('active');
            document.getElementById(`${tabName}-tab`).classList.add('active');
        });
    });
});

/**
 * Mettre à jour le statut dans la base de données
 */
async function updateApprovalStatus(id, type, approved) {
    try {
        const response = await fetch(getFunctionUrl('updateData.php'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                table: type,
                id: id,
                data: {
                    approved: approved
                }
            })
        });
        
        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.error || 'Erreur lors de la mise à jour');
        }
        
        return true;
    } catch (error) {
        console.error('Erreur updateApprovalStatus:', error);
        throw error;
    }
}

/**
 * Approuver un élément
 */
async function approveItem(id, type) {
    if (confirm(`Êtes-vous sûr de vouloir approuver ce ${type === 'candidate' ? 'candidat' : 'poste'} ?`)) {
        try {
            // Mettre à jour dans la base de données (approved = 1)
            await updateApprovalStatus(id, type, 1);
            
            // Recharger la page pour afficher le nouveau statut
            window.location.reload();
        } catch (error) {
            alert(`❌ Erreur lors de l'approbation: ${error.message}`);
        }
    }
}

/**
 * Refuser un élément
 */
async function rejectItem(id, type) {
    if (confirm(`Êtes-vous sûr de vouloir refuser ce ${type === 'candidate' ? 'candidat' : 'poste'} ?`)) {
        try {
            // Mettre à jour dans la base de données (approved = 0)
            await updateApprovalStatus(id, type, 0);
            
            // Recharger la page pour afficher le nouveau statut
            window.location.reload();
        } catch (error) {
            alert(`❌ Erreur lors du refus: ${error.message}`);
        }
    }
}

/**
 * Voir un candidat
 */
function viewCandidate(id) {
    window.location.href = getAbsolutePath(`data/candidates/${id}`);
}

/**
 * Voir une offre d'emploi
 */
function viewJob(id) {
    window.location.href = getAbsolutePath(`data/jobs/${id}`);
}
