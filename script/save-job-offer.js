// Script pour sauvegarder l'offre d'emploi
document.addEventListener('DOMContentLoaded', () => {
    const saveButton = document.getElementById('save-job-button');
    
    if (saveButton) {
        saveButton.addEventListener('click', async () => {
            try {
                // Récupérer le conteneur principal
                const jobContainer = document.querySelector('.job-offer-container');
                if (!jobContainer) {
                    alert('❌ Erreur: Conteneur offre d\'emploi introuvable');
                    return;
                }
                
                const jobId = jobContainer.getAttribute('data-id');
                const mode = jobContainer.getAttribute('data-mode') || 'update';
                
                // Collecter toutes les données
                const dataByTable = collectJobDataByTable();
                
                console.log('=== DEBUT SAUVEGARDE OFFRE ===');
                console.log('Données collectées:', dataByTable);
                console.log('Mode:', mode, 'Job ID:', jobId);
                
                // Sauvegarder les données selon le mode
                if (mode === 'insert') {
                    console.log('MODE INSERT');
                    await insertAllJobData(dataByTable);
                } else {
                    console.log('MODE UPDATE');
                    await updateAllJobData(dataByTable, jobId);
                }
                
                console.log('=== FIN SAUVEGARDE OFFRE ===');
                
                // Convertir l'offre en HTML statique
                if (jobId) {
                    await convertJobToHTML(jobId);
                }
                
                alert('✅ Offre d\'emploi sauvegardée avec succès !');
                
                // Recharger la page pour afficher les données mises à jour
                window.location.reload();
                
            } catch (error) {
                console.error('Erreur lors de la sauvegarde:', error);
                alert('❌ Erreur lors de la sauvegarde de l\'offre d\'emploi: ' + error.message);
            }
        });
    }
});

/**
 * Collecte toutes les données de l'offre d'emploi par table
 */
function collectJobDataByTable() {
    const dataByTable = {
        job: {},
        company: {},
        contact: {},
        requirement: {}
    };
    
    // Récupérer tous les éléments éditables
    const editableElements = document.querySelectorAll('[data-table][data-column]');
    
    editableElements.forEach(element => {
        const table = element.getAttribute('data-table');
        const column = element.getAttribute('data-column');
        const id = element.getAttribute('data-id');
        let value = element.getAttribute('data-value') || element.textContent.trim();
        
        // Vérifier que la table existe dans notre structure
        if (!dataByTable[table]) {
            console.warn(`Table ${table} non gérée, ignorée`);
            return;
        }
        
        // Initialiser l'objet de la table si nécessaire
        if (!dataByTable[table][id]) {
            dataByTable[table][id] = { id: id };
        }
        
        // Stocker la valeur
        dataByTable[table][id][column] = value;
        
        console.log(`📝 ${table}.${column} (ID: ${id}) = ${value}`);
    });
    
    return dataByTable;
}

/**
 * Insère toutes les données en base de données (mode insert)
 */
async function insertAllJobData(dataByTable) {
    const insertedIds = {};
    
    // Ordre d'insertion : contact, company, requirement, puis job
    const insertOrder = ['contact', 'company', 'requirement', 'job'];
    
    for (const table of insertOrder) {
        const tableData = dataByTable[table];
        
        for (const [recordId, data] of Object.entries(tableData)) {
            console.log(`📝 Insertion dans ${table}:`, data);
            
            try {
                const response = await fetch(getFunctionUrl('insertData.php'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        table: table,
                        data: data
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    insertedIds[table] = insertedIds[table] || {};
                    insertedIds[table][recordId] = result.id;
                    console.log(`✅ ${table} inséré avec ID:`, result.id);
                } else {
                    throw new Error(`Erreur insertion ${table}: ${result.error}`);
                }
            } catch (error) {
                console.error(`❌ Erreur insertion ${table}:`, error);
                throw error;
            }
        }
    }
    
    return insertedIds;
}

/**
 * Met à jour toutes les données en base de données (mode update)
 */
async function updateAllJobData(dataByTable, mainJobId) {
    const updatePromises = [];
    
    // Parcourir chaque table
    for (const [table, records] of Object.entries(dataByTable)) {
        // Parcourir chaque enregistrement de la table
        for (const [recordId, data] of Object.entries(records)) {
            // Ne pas envoyer l'ID dans les données
            const { id, ...dataToUpdate } = data;
            
            // Ignorer si pas de données à mettre à jour
            if (Object.keys(dataToUpdate).length === 0) {
                console.log(`⏭️ Pas de données à mettre à jour pour ${table} ID ${recordId}`);
                continue;
            }
            
            console.log(`📝 Mise à jour ${table} ID ${recordId}:`, dataToUpdate);
            
            // Créer la promesse de mise à jour
            const updatePromise = fetch(getFunctionUrl('updateData.php'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    table: table,
                    id: recordId,
                    data: dataToUpdate
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log(`✅ ${table} ID ${recordId} mis à jour`);
                    return { success: true, table, id: recordId };
                } else {
                    throw new Error(`Erreur mise à jour ${table} ID ${recordId}: ${result.error}`);
                }
            })
            .catch(error => {
                console.error(`❌ Erreur mise à jour ${table} ID ${recordId}:`, error);
                throw error;
            });
            
            updatePromises.push(updatePromise);
        }
    }
    
    // Attendre toutes les mises à jour
    if (updatePromises.length > 0) {
        const results = await Promise.all(updatePromises);
        console.log('✅ Toutes les mises à jour terminées:', results);
    } else {
        console.log('⚠️ Aucune donnée à mettre à jour');
    }
}

/**
 * Bouton reset
 */
document.addEventListener('DOMContentLoaded', () => {
    const resetButton = document.getElementById('reset-job-button');
    
    if (resetButton) {
        resetButton.addEventListener('click', () => {
            if (confirm('⚠️ Êtes-vous sûr de vouloir recharger la page ? Les modifications non sauvegardées seront perdues.')) {
                window.location.reload();
            }
        });
    }
});

/**
 * Bouton print
 */
document.addEventListener('DOMContentLoaded', () => {
    const printButton = document.getElementById('print-job-button');
    
    if (printButton) {
        printButton.addEventListener('click', () => {
            window.print();
        });
    }
});
