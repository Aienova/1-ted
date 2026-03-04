<!-- Bandeau RGPD / Cookie Consent -->
<div id="gdpr-banner" class="gdpr-banner" style="display: none;">
    <div class="gdpr-overlay"></div>
    
    <div class="gdpr-container">
        <div class="gdpr-header">
            <h2 class="gdpr-title">🍪 <?php echo $textData['gdprTitle'] ?? 'Gestion des cookies'; ?></h2>
            <p class="gdpr-description">
                <?php echo $textData['gdprDescription'] ?? "Nous utilisons des cookies pour améliorer votre expérience sur notre site. Vous pouvez accepter tous les cookies, les refuser ou personnaliser vos préférences. Pour plus d'informations, veuillez consulter notre politique de confidentialité :"; ?>
                <a href="/<?php echo $lang; ?>/policy" class="gdpr-link" target="_blank">
                    📄 <?php echo $textData['gdprPrivacyPolicy'] ?? 'Politique de confidentialité'; ?>
                </a>
            </p>
        </div>

        <div class="gdpr-content">
            <!-- Vue Simple (par défaut) -->
            <div id="gdpr-simple-view" class="gdpr-view">
                <div class="gdpr-actions-simple">
                    <button id="gdpr-accept-all" class="gdpr-btn gdpr-btn-primary">
                        ✓ <?php echo $textData['gdprAcceptAll'] ?? 'Accepter tout'; ?>
                    </button>
                    <button id="gdpr-reject-all" class="gdpr-btn gdpr-btn-secondary">
                        ✗ <?php echo $textData['gdprRejectAll'] ?? 'Tout refuser'; ?>
                    </button>
                    <button id="gdpr-customize" class="gdpr-btn gdpr-btn-outline">
                        ⚙️ <?php echo $textData['gdprCustomize'] ?? 'Personnaliser'; ?>
                    </button>
                </div>
            </div>

            <!-- Vue Détaillée (personnalisation) -->
            <div id="gdpr-detailed-view" class="gdpr-view" style="display: none;">
                <div class="gdpr-cookies-list">
                    
                    <!-- Cookies Nécessaires (toujours actifs) -->
                    <div class="gdpr-cookie-item">
                        <div class="gdpr-cookie-header">
                            <div class="gdpr-cookie-info">
                                <h3 class="gdpr-cookie-title">
                                    🔒 <?php echo $textData['gdprNecessary'] ?? 'Cookies nécessaires'; ?>
                                </h3>
                                <p class="gdpr-cookie-desc">
                                    <?php echo $textData['gdprNecessaryDesc'] ?? 'Ces cookies sont essentiels au fonctionnement du site et ne peuvent pas être désactivés.'; ?>
                                </p>
                            </div>
                            <div class="gdpr-cookie-toggle">
                                <input type="checkbox" id="cookie-necessary" checked disabled>
                                <label for="cookie-necessary" class="gdpr-toggle-label disabled">
                                    <span class="gdpr-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Cookies Analytiques -->
                    <div class="gdpr-cookie-item">
                        <div class="gdpr-cookie-header">
                            <div class="gdpr-cookie-info">
                                <h3 class="gdpr-cookie-title">
                                    📊 <?php echo $textData['gdprAnalytics'] ?? 'Cookies analytiques'; ?>
                                </h3>
                                <p class="gdpr-cookie-desc">
                                    <?php echo $textData['gdprAnalyticsDesc'] ?? 'Ces cookies nous aident à comprendre comment les visiteurs interagissent avec notre site en collectant des informations de manière anonyme.'; ?>
                                </p>
                            </div>
                            <div class="gdpr-cookie-toggle">
                                <input type="checkbox" id="cookie-analytics" class="gdpr-checkbox">
                                <label for="cookie-analytics" class="gdpr-toggle-label">
                                    <span class="gdpr-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Cookies de Marketing -->
                    <div class="gdpr-cookie-item">
                        <div class="gdpr-cookie-header">
                            <div class="gdpr-cookie-info">
                                <h3 class="gdpr-cookie-title">
                                    📢 <?php echo $textData['gdprMarketing'] ?? 'Cookies marketing'; ?>
                                </h3>
                                <p class="gdpr-cookie-desc">
                                    <?php echo $textData['gdprMarketingDesc'] ?? 'Ces cookies sont utilisés pour afficher des publicités pertinentes et suivre l\'efficacité de nos campagnes.'; ?>
                                </p>
                            </div>
                            <div class="gdpr-cookie-toggle">
                                <input type="checkbox" id="cookie-marketing" class="gdpr-checkbox">
                                <label for="cookie-marketing" class="gdpr-toggle-label">
                                    <span class="gdpr-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Cookies de Préférences -->
                    <div class="gdpr-cookie-item">
                        <div class="gdpr-cookie-header">
                            <div class="gdpr-cookie-info">
                                <h3 class="gdpr-cookie-title">
                                    ⚙️ <?php echo $textData['gdprPreferences'] ?? 'Cookies de préférences'; ?>
                                </h3>
                                <p class="gdpr-cookie-desc">
                                    <?php echo $textData['gdprPreferencesDesc'] ?? 'Ces cookies permettent de mémoriser vos choix (langue, région) pour une expérience personnalisée.'; ?>
                                </p>
                            </div>
                            <div class="gdpr-cookie-toggle">
                                <input type="checkbox" id="cookie-preferences" class="gdpr-checkbox">
                                <label for="cookie-preferences" class="gdpr-toggle-label">
                                    <span class="gdpr-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="gdpr-actions-detailed">
                    <button id="gdpr-save-preferences" class="gdpr-btn gdpr-btn-primary">
                        💾 <?php echo $textData['gdprSavePreferences'] ?? 'Enregistrer mes préférences'; ?>
                    </button>
                    <button id="gdpr-back" class="gdpr-btn gdpr-btn-outline">
                        ← <?php echo $textData['gdprBack'] ?? 'Retour'; ?>
                    </button>
                </div>
            </div>
        </div>


    </div>
</div>
