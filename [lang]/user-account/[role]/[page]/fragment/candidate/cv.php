<?php
/**
 * Page CV du candidat - Affiche le CV complet avec éditeur
 */

// Récupérer les fonctions nécessaires
require_once __DIR__ . '/../../../../../../function/getData.php';
require_once __DIR__ . '/../../../../../../function/connectionDatabase.php';

// Charger la configuration pour l'URL de base
$envFile = __DIR__ . '/../../../../../../local.env';
$baseUrl = '';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, 'BASE_URL=') === 0) {
            $baseUrl = trim(substr($line, 9));
            break;
        }
    }
}

try {
    // Vérifier l'authentification
    if (!isset($_SESSION['accountId']) || !isset($_SESSION['role'])) {
        echo '<div style="padding: 2rem; text-align: center; color: #ef4444;">
                <h2>⚠️ Non authentifié</h2>
                <p>Vous devez être connecté pour accéder à cette page.</p>
              </div>';
        exit;
    }
    
    if ($_SESSION['role'] !== 'candidate') {
        echo '<div style="padding: 2rem; text-align: center; color: #ef4444;">
                <h2>⚠️ Accès refusé</h2>
                <p>Cette page est réservée aux candidats.</p>
              </div>';
        exit;
    }
    
    $db = getDatabaseConnection();
    
    // Récupérer l'ID du candidat depuis la session ou la base de données
    if (isset($_SESSION['candidate_id'])) {
        $candidateId = $_SESSION['candidate_id'];
    } else {
        $stmt = $db->prepare("SELECT candidate_id FROM account WHERE id = :account_id");
        $stmt->execute([':account_id' => $_SESSION['accountId']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $candidateId = $result['candidate_id'] ?? null;
        
        if ($candidateId) {
            $_SESSION['candidate_id'] = $candidateId;
        }
    }
    
    // Si aucun candidat n'est associé, afficher un bouton pour créer le CV
    if (!$candidateId) {
        ?>
        <div style="padding: 3rem; text-align: center;">
            <div style="max-width: 600px; margin: 0 auto; background: #f9fafb; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📄</div>
                <h2 style="color: #1f2937; margin-bottom: 1rem;">Aucun CV créé</h2>
                <p style="color: #6b7280; margin-bottom: 2rem;">Vous n'avez pas encore créé votre CV. Créez-le maintenant pour commencer à postuler aux offres d'emploi !</p>
                <button id="createCvButton" style="
                    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                    color: white;
                    border: none;
                    padding: 1rem 2rem;
                    font-size: 1.1rem;
                    font-weight: 600;
                    border-radius: 8px;
                    cursor: pointer;
                    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
                    transition: all 0.3s ease;
                " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(59, 130, 246, 0.4)';" 
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(59, 130, 246, 0.3)';">
                    <span id="btnText">⏳ Chargement des scripts...</span>
                    <span id="btnLoader" style="display: none;">⏳ Ouverture...</span>
                </button>
            </div>
        </div>

        <!-- Popup pour créer le CV -->
        <div id="cvPopup" style="
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            overflow-y: auto;
            padding: 20px;
        ">
            <div style="
                max-width: 1200px;
                margin: 20px auto;
                background: white;
                border-radius: 12px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                position: relative;
            ">
                <div style="
                    top: 0;
                    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                    color: white;
                    padding: 1.5rem 2rem;
                    border-radius: 12px 12px 0 0;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    z-index: 10;
                ">
                    <h2 style="margin: 0; font-size: 1.5rem;">📝 Créer mon CV</h2>
                    <button id="closeCvPopup" style="
                        background: rgba(255,255,255,0.2);
                        border: none;
                        color: white;
                        font-size: 1.5rem;
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        cursor: pointer;
                        transition: background 0.3s;
                    " onmouseover="this.style.background='rgba(255,255,255,0.3)';" 
                       onmouseout="this.style.background='rgba(255,255,255,0.2)';">
                        ✕
                    </button>
                </div>
                <div id="cvPopupContent" style="padding: 2rem;">
                    <div style="text-align: center; padding: 3rem; color: #6b7280;">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">⏳</div>
                        <p>Chargement du formulaire...</p>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Styles pour le formulaire CV dans la popup */
            #cvPopup .cv-container {
                max-width: 100% !important;
                margin: 0 !important;
                box-shadow: none !important;
            }
            
            #cvPopup .button-container {
                position: sticky;
                bottom: 0;
                background: white;
                padding: 1rem;
                border-top: 2px solid #e5e7eb;
                margin: 0 -2rem -2rem -2rem;
                border-radius: 0 0 12px 12px;
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
                z-index: 11; /* Au-dessus du contenu mais pas au-dessus de la popup */
            }
            
            #cvPopup .edit-cv-button {
                margin: 0 !important;
            }
            
            /* S'assurer que la popup laisse passer les événements au contenu */
            #cvPopup {
                pointer-events: auto;
            }
            
            #cvPopupContent {
                pointer-events: auto;
            }
        </style>

        <script>
            // Fonction pour attendre que les scripts soient chargés
            function waitForScripts(timeout = 10000) {
                return new Promise((resolve, reject) => {
                    const startTime = Date.now();
                    let checkCount = 0;
                    
                    function check() {
                        checkCount++;
                        const elapsed = Date.now() - startTime;
                        
                        // Vérifier que les scripts essentiels sont chargés
                        const hasEditorManagerClass = typeof EditorManager !== 'undefined';
                        const hasInitFunction = typeof initEditorManager === 'function';
                        const hasEditorInstance = typeof editorManager !== 'undefined';
                        
                        console.log(`🔍 Check #${checkCount} (${elapsed}ms):`, {
                            EditorManager: hasEditorManagerClass ? '✅' : '❌',
                            initEditorManager: hasInitFunction ? '✅' : '❌', 
                            editorManager: hasEditorInstance ? '✅' : '❌'
                        });
                        
                        const scriptsLoaded = hasEditorManagerClass && hasInitFunction;
                        
                        if (scriptsLoaded) {
                            console.log('✅ Tous les scripts nécessaires sont chargés');
                            resolve();
                        } else if (elapsed > timeout) {
                            const missing = [];
                            if (!hasEditorManagerClass) missing.push('EditorManager');
                            if (!hasInitFunction) missing.push('initEditorManager');
                            
                            reject(new Error(`Timeout après ${timeout}ms. Scripts manquants: ${missing.join(', ')}`));
                        } else {
                            setTimeout(check, 100);
                        }
                    }
                    
                    check();
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                const createCvButton = document.getElementById('createCvButton');
                const cvPopup = document.getElementById('cvPopup');
                const closeCvPopup = document.getElementById('closeCvPopup');
                const cvPopupContent = document.getElementById('cvPopupContent');
                const btnText = document.getElementById('btnText');
                const btnLoader = document.getElementById('btnLoader');

                // Vérifier immédiatement si les scripts sont chargés
                console.log('🚀 Initialisation de la page CV...');
                console.log('📦 Scripts disponibles:', {
                    EditorManager: typeof EditorManager !== 'undefined',
                    initEditorManager: typeof initEditorManager === 'function',
                    editorManager: typeof editorManager !== 'undefined'
                });

                // Attendre que tous les scripts soient chargés avant d'activer le bouton
                waitForScripts(15000).then(() => {
                    console.log('✅ Tous les scripts sont prêts, bouton activé');
                    btnText.innerHTML = '✨ Créer mon CV';
                }).catch((error) => {
                    console.error('❌ Impossible de charger tous les scripts:', error);
                    btnText.innerHTML = '⚠️ Créer mon CV (scripts en cours)';
                });

                // Ouvrir la popup
                createCvButton.addEventListener('click', async function() {
                    // Afficher le loader
                    const btnText = document.getElementById('btnText');
                    const btnLoader = document.getElementById('btnLoader');
                    btnText.style.display = 'none';
                    btnLoader.style.display = 'inline';
                    createCvButton.disabled = true;
                    
                    // D'abord attendre que tous les scripts soient chargés
                    try {
                        await waitForScripts();
                    } catch (error) {
                        console.error('❌ Erreur chargement scripts:', error);
                        
                        // Message d'erreur plus détaillé
                        const errorDetails = `
                            <div style="text-align: center; padding: 2rem; color: #ef4444;">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
                                <h3>Scripts en cours de chargement</h3>
                                <p>Les scripts nécessaires ne sont pas encore tous chargés.</p>
                                <p style="color: #6b7280; font-size: 0.9rem; margin-top: 1rem;">
                                    Erreur: ${error.message}
                                </p>
                                <p style="color: #6b7280; font-size: 0.9rem;">
                                    Veuillez patienter quelques secondes et réessayer.
                                </p>
                                <button onclick="this.parentElement.remove()" style="
                                    background: #3b82f6;
                                    color: white;
                                    border: none;
                                    padding: 0.75rem 1.5rem;
                                    border-radius: 6px;
                                    cursor: pointer;
                                    margin-top: 1rem;
                                ">
                                    Compris
                                </button>
                            </div>
                        `;
                        
                        // Afficher dans un overlay temporaire
                        const overlay = document.createElement('div');
                        overlay.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99999; display: flex; align-items: center; justify-content: center;';
                        overlay.innerHTML = `<div style="background: white; border-radius: 12px; max-width: 500px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">${errorDetails}</div>`;
                        document.body.appendChild(overlay);
                        overlay.querySelector('button').addEventListener('click', () => overlay.remove());
                        
                        // Réactiver le bouton
                        btnText.style.display = 'inline';
                        btnLoader.style.display = 'none';
                        createCvButton.disabled = false;
                        return;
                    }
                    
                    // Réinitialiser le bouton
                    btnText.style.display = 'inline';
                    btnLoader.style.display = 'none';
                    createCvButton.disabled = false;
                    
                    cvPopup.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                    
                    try {
                        // Charger le contenu de add-cv.php
                        const baseUrl = '<?php echo $baseUrl; ?>';
                        const response = await fetch(baseUrl + '/data/candidates/add-cv.php');
                        
                        if (!response.ok) {
                            throw new Error('Erreur lors du chargement du formulaire');
                        }
                        
                        const html = await response.text();
                        
                        // Extraire seulement le body du HTML
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const bodyContent = doc.body.innerHTML;
                        
                        cvPopupContent.innerHTML = bodyContent;
                        
                        // Ajouter un contexte pour indiquer qu'on est dans l'espace compte
                        const cvContainer = cvPopupContent.querySelector('.cv-container');
                        if (cvContainer) {
                            cvContainer.setAttribute('data-user-account-context', 'true');
                            cvContainer.setAttribute('data-account-id', '<?php echo $_SESSION['accountId'] ?? ''; ?>');
                        }
                        
                        // Écouter l'événement de création réussie du CV
                        window.addEventListener('cvCreatedSuccess', function(e) {
                            console.log('✅ CV créé avec succès, rafraîchissement de la page...');
                            // Fermer la popup
                            closePopup();
                            // Rafraîchir la page pour afficher le CV
                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        }, { once: true });
                        
                        // Les scripts sont déjà chargés dans index.php, maintenant initialiser l'éditeur
                        console.log('✅ Formulaire CV chargé avec succès.');
                        
                        // Forcer l'initialisation de l'EditorManager
                        if (typeof initEditorManager === 'function') {
                            const manager = initEditorManager();
                            console.log('✅ EditorManager initialisé:', manager);
                        } else {
                            console.error('❌ initEditorManager non disponible');
                        }
                        
                        // Double vérification que l'EditorManager est maintenant disponible
                        if (typeof editorManager !== 'undefined' && editorManager) {
                            console.log('✅ EditorManager détecté et prêt à fonctionner');
                        } else {
                            console.error('❌ EditorManager toujours non trouvé après initialisation');
                        }
                        
                        // Ajouter un indicateur visuel sur les éléments éditables
                        const editableElements = cvPopupContent.querySelectorAll('.canEdit');
                        console.log(`📝 ${editableElements.length} éléments éditables trouvés`);
                        
                        if (editableElements.length === 0) {
                            console.warn('⚠️ Aucun élément .canEdit trouvé dans le formulaire');
                        }
                        
                        // Ajouter un effet hover pour indiquer que c'est cliquable
                        editableElements.forEach((el, index) => {
                            el.style.cursor = 'pointer';
                            el.style.transition = 'all 0.2s';
                            
                            el.addEventListener('mouseenter', function() {
                                this.style.outline = '2px dashed #3b82f6';
                                this.style.outlineOffset = '2px';
                                this.style.backgroundColor = 'rgba(59, 130, 246, 0.05)';
                            });
                            
                            el.addEventListener('mouseleave', function() {
                                this.style.outline = 'none';
                                this.style.backgroundColor = 'transparent';
                            });
                            
                            // Test: ajouter un click listener direct pour debug
                            el.addEventListener('click', function(e) {
                                console.log(`🖱️ Clic sur élément éditable #${index}:`, {
                                    editor: el.getAttribute('data-editor'),
                                    element: el
                                });
                            });
                        });
                        
                    } catch (error) {
                        console.error('Erreur:', error);
                        cvPopupContent.innerHTML = `
                            <div style="text-align: center; padding: 3rem; color: #ef4444;">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">❌</div>
                                <h3>Erreur de chargement</h3>
                                <p>${error.message}</p>
                                <button onclick="location.reload()" style="
                                    background: #3b82f6;
                                    color: white;
                                    border: none;
                                    padding: 0.75rem 1.5rem;
                                    border-radius: 6px;
                                    cursor: pointer;
                                    margin-top: 1rem;
                                ">
                                    Réessayer
                                </button>
                            </div>
                        `;
                    }
                });

                // Fermer la popup
                function closePopup() {
                    cvPopup.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }

                closeCvPopup.addEventListener('click', closePopup);
                
                // Fermer en cliquant en dehors (mais pas sur le contenu intérieur)
                cvPopup.addEventListener('click', function(e) {
                    // Seulement fermer si on clique directement sur la popup (pas sur son contenu)
                    if (e.target === cvPopup) {
                        closePopup();
                    }
                });
                
                // NE PAS bloquer la propagation des événements vers le contenu
                // Ceci permet aux éditeurs de fonctionner
                const popupContent = document.getElementById('cvPopupContent');
                if (popupContent) {
                    popupContent.addEventListener('click', function(e) {
                        // Laisser les événements se propager normalement
                        console.log('👆 Clic détecté dans le contenu de la popup');
                    });
                }
                
                // Fermer avec la touche Échap
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && cvPopup.style.display === 'block') {
                        closePopup();
                    }
                });
            });
        </script>
        <?php
        exit;
    }
    
    // Charger l'objet candidat complet
    $candidate = getData('candidate', $candidateId);
    
    if (!$candidate) {
        echo '<div style="padding: 2rem; text-align: center; color: #ef4444;">
                <h2>⚠️ Erreur</h2>
                <p>Impossible de charger vos données de CV.</p>
              </div>';
        exit;
    }
    
    // Inclure le fichier edit-cv.php qui contient la fonction importCandidate
    include __DIR__ . '/../../../../../../data/candidates/edit-cv.php';
    
    // Appeler la fonction pour afficher le CV (isUserAccount = true car on est dans l'espace compte)
    importCandidate($candidate, true);
    
} catch (Exception $e) {
    echo '<div style="padding: 2rem; text-align: center; color: #ef4444;">
            <h2>⚠️ Erreur</h2>
            <p>Une erreur s\'est produite lors du chargement du CV: ' . htmlspecialchars($e->getMessage()) . '</p>
          </div>';
}
?>

