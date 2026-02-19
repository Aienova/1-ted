<section class="partnership-section">
    <div class="container">
        <div class="partnership-intro">
            <h2>Devenez Partenaire de 1-TED</h2>
            <p>Rejoignez notre réseau de partenaires et bénéficiez de notre plateforme innovante pour trouver les meilleurs talents ou développer votre activité de recrutement.</p>
        </div>

        <form class="partnership-form" method="POST" action="">
            <h3>Informations sur votre entreprise</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="companyName">Nom de l'entreprise *</label>
                    <input type="text" id="companyName" name="companyName" required>
                </div>
                <div class="form-group">
                    <label for="siret">SIRET</label>
                    <input type="text" id="siret" name="siret">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="sector">Secteur d'activité *</label>
                    <select id="sector" name="sector" required>
                        <option value="">Sélectionnez un secteur</option>
                        <option value="it">Informatique / Tech</option>
                        <option value="finance">Finance</option>
                        <option value="health">Santé</option>
                        <option value="commerce">Commerce</option>
                        <option value="industry">Industrie</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="size">Taille de l'entreprise *</label>
                    <select id="size" name="size" required>
                        <option value="">Sélectionnez</option>
                        <option value="1-10">1-10 employés</option>
                        <option value="11-50">11-50 employés</option>
                        <option value="51-200">51-200 employés</option>
                        <option value="201+">201+ employés</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="website">Site web</label>
                <input type="url" id="website" name="website" placeholder="https://">
            </div>

            <h3>Contact principal</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">Prénom *</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Nom *</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Téléphone *</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
            </div>

            <div class="form-group">
                <label for="position">Fonction *</label>
                <input type="text" id="position" name="position" required>
            </div>

            <h3>Votre besoin</h3>

            <div class="form-group">
                <label for="partnershipType">Type de partenariat *</label>
                <select id="partnershipType" name="partnershipType" required>
                    <option value="">Sélectionnez</option>
                    <option value="recruitment">Recrutement de talents</option>
                    <option value="service">Prestataire de services RH</option>
                    <option value="technology">Partenaire technologique</option>
                    <option value="other">Autre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Présentez votre projet de partenariat *</label>
                <textarea id="message" name="message" rows="6" required></textarea>
            </div>

            <div class="form-group checkbox-group">
                <label>
                    <input type="checkbox" name="terms" required>
                    J'accepte les <a href="/<?php echo $lang; ?>/terms" target="_blank">conditions d'utilisation</a> et la <a href="/<?php echo $lang; ?>/policy" target="_blank">politique de confidentialité</a>
                </label>
            </div>

            <button type="submit" class="btn-submit">Envoyer la demande</button>
        </form>
    </div>
</section>
