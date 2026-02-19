<section class="contact-section">
    <div class="container">
        <div class="contact-content">
            <div class="contact-info">
                <h2>Contactez-nous</h2>
                <p>Vous avez une question ? N'hésitez pas à nous contacter via le formulaire ci-contre ou directement par les moyens suivants :</p>
                
                <div class="contact-details">
                    <div class="contact-item">
                        <h3>📧 Email</h3>
                        <p>contact@1ted.com</p>
                    </div>
                    <div class="contact-item">
                        <h3>📞 Téléphone</h3>
                        <p>+33 1 23 45 67 89</p>
                    </div>
                    <div class="contact-item">
                        <h3>📍 Adresse</h3>
                        <p>123 Avenue des Champs-Élysées<br>75008 Paris, France</p>
                    </div>
                    <div class="contact-item">
                        <h3>🕒 Horaires</h3>
                        <p>Lundi - Vendredi : 9h00 - 18h00</p>
                    </div>
                </div>
            </div>

            <div class="contact-form-container">
                <form class="contact-form" method="POST" action="">
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

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="tel" id="phone" name="phone">
                    </div>

                    <div class="form-group">
                        <label for="subject">Sujet *</label>
                        <select id="subject" name="subject" required>
                            <option value="">Sélectionnez un sujet</option>
                            <option value="general">Question générale</option>
                            <option value="support">Support technique</option>
                            <option value="partnership">Partenariat</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Envoyer le message</button>
                </form>
            </div>
        </div>
    </div>
</section>
