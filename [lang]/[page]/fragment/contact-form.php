
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
                        <p>+33 7 53 47 59 13</p>
                    </div>

                    <div class="contact-item">
                        <h3>🕒 Horaires</h3>
                        <p>Lundi - Vendredi : 9h00 - 18h00</p>
                    </div>
                </div>
            </div>

            <div class="contact-form-container" id="contactFormContainer">

                <!-- Loader (caché par défaut) -->
                <div id="contactLoader" style="display:none; text-align:center; padding: 60px 20px;">
                    <img src="/media/image/aienova-loader.gif" alt="Chargement..." class="loader invert">
                    <p style="margin-top: 16px; color: #555;">Envoi en cours...</p>
                </div>

                <!-- Message de succès (caché par défaut) -->
                <div id="contactSuccess" style="display:none; text-align:center; padding: 40px 20px;">
                    <span style="font-size: 48px;">✅</span>
                    <h2 style="color: #27ae60; margin-top: 15px;">Message envoyé !</h2>
                    <p>Nous avons bien reçu votre message.<br>Nous vous répondrons dans les plus brefs délais.</p>
                    <p style="margin-top: 20px; font-size: 13px; color: #777;">Un email de confirmation vous a été envoyé.</p>
                </div>

                <!-- Message d'erreur (caché par défaut) -->
                <div id="contactError" style="display:none; background:#fdecea; border-left: 4px solid #e74c3c; padding: 12px 16px; margin-bottom: 20px; border-radius: 4px; color: #c0392b;"></div>

                <!-- Formulaire -->
                <form class="contact-form" id="contactForm">
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

                    <!-- Captcha "Je ne suis pas un robot" -->
                    <div class="form-group captcha-group">
                        <label class="captcha-label">
                            <input type="checkbox" id="humanCheck" name="humanCheck">
                            <span class="captcha-box">
                                <span class="captcha-checkmark">✓</span>
                            </span>
                            <span class="captcha-text">Je ne suis pas un robot</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit" id="contactSubmit" disabled>Envoyer le message</button>
                </form>
            </div>

            <script src="/script/config.js"></script>
            <script src="/script/email/sendContactMail.js"></script>
        </div>
    </div>
</section>
