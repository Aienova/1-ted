<?php
/**
 * Composant de popup générique
 * 
 * Variables attendues:
 * - $popupId: ID unique de la popup (requis)
 * - $popupTitle: Titre de la popup (optionnel)
 * - $popupClass: Classes CSS supplémentaires (optionnel)
 * - $popupSize: Taille de la popup ('small', 'medium', 'large', 'fullscreen') - défaut: 'large'
 */

$popupId = $popupId ?? 'popup';
$popupTitle = $popupTitle ?? '';
$popupClass = $popupClass ?? '';
$popupSize = $popupSize ?? 'large';
?>

<div id="<?php echo $popupId; ?>" class="popup popup-<?php echo $popupSize; ?> <?php echo $popupClass; ?>">
    <div class="popup-overlay"></div>
    <div class="popup-container">
        <div class="popup-header">
            <?php if ($popupTitle): ?>
                <h2 class="popup-title"><?php echo htmlspecialchars($popupTitle); ?></h2>
            <?php endif; ?>
            <button class="popup-close" aria-label="Fermer">
                <span>✕</span>
            </button>
        </div>
        <div class="popup-content">
            <!-- Le contenu sera chargé dynamiquement ici -->
        </div>
    </div>
</div>

<style>
/* Popup overlay */
.popup {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 111111111;
    display: none;
    align-items: center;
    justify-content: center;
}

.popup.active {
    display: flex;
}

.popup-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    animation: fadeIn 0.3s ease;
}

/* Container de la popup */
.popup-container {
    position: relative;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    animation: slideUp 0.3s ease;
    overflow: hidden;
}

/* Tailles */
.popup-small .popup-container {
    width: 90%;
    max-width: 500px;
}

.popup-medium .popup-container {
    width: 90%;
    max-width: 800px;
}

.popup-large .popup-container {
    width: 95%;
    max-width: 1200px;
}

.popup-fullscreen .popup-container {
    width: 100%;
    height: 100%;
    max-height: 100vh;
    border-radius: 0;
}

/* Header */
.popup-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 25px;
    border-bottom: 2px solid #e5e7eb;
    background: #f9fafb;
}

.popup-title {
    margin: 0;
    font-size: 1.5rem;
    color: #1f2937;
    font-weight: 700;
}

.popup-close {
    width: 40px;
    height: 40px;
    border: none;
    background: white;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #6b7280;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.popup-close:hover {
    background: #ef4444;
    color: white;
    transform: rotate(90deg);
}

/* Contenu */
.popup-content {
    flex: 1;
    overflow-y: auto;
    padding: 25px;
}

/* Loader */
.popup-loader {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    color: #6b7280;
}

.popup-loader .spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e5e7eb;
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.popup-loader p {
    margin-top: 20px;
    font-size: 1rem;
}

/* Error */
.popup-error {
    text-align: center;
    padding: 60px 20px;
}

.popup-error .error-icon {
    font-size: 4rem;
    opacity: 0.3;
}

.popup-error h3 {
    color: #374151;
    margin: 20px 0 10px;
}

.popup-error p {
    color: #6b7280;
    margin: 0 0 30px;
}

.btn-close-error {
    padding: 10px 24px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-close-error:hover {
    background: #5568d3;
    transform: translateY(-2px);
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .popup-container {
        width: 100% !important;
        height: 100% !important;
        max-height: 100vh !important;
        border-radius: 0 !important;
    }
    
    .popup-header {
        padding: 15px 20px;
    }
    
    .popup-title {
        font-size: 1.2rem;
    }
    
    .popup-content {
        padding: 20px;
    }
}
</style>
