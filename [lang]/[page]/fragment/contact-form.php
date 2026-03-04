<?php


    $titleText           = $textData['titleText']           ?? "Contactez-nous";
    $youhaveAQuestionText = $textData['youhaveAQuestionText'] ?? "";
    $emailText           = $textData['emailText']           ?? "Email";
    $phoneText           = $textData['phoneText']           ?? "Téléphone";
    $hoursText           = $textData['hoursText']           ?? "Heures d'ouverture";
    $subjectText         = $textData['subjectText']         ?? "Sujet";
    $selectSubjectText   = $textData['selectSubjectText']   ?? "Sélectionnez un sujet";
    $generalQuestionText = $textData['generalQuestionText'] ?? "Question générale";
    $technicalSupportText = $textData['technicalSupportText'] ?? "Support technique";
    $partnershipText     = $textData['partnershipText']     ?? "Partenariat";
    $otherText           = $textData['otherText']           ?? "Autre";
    $messageText         = $textData['messageText']         ?? "Message";
    $captchaText         = $textData['captchaText']         ?? "Je ne suis pas un robot";
    $sendMessageText     = $textData['sendMessageText']     ?? "Envoyer le message";
    $firstNameText       = $textData['firstNameText']       ?? "Prénom";
    $lastNameText        = $textData['lastNameText']        ?? "Nom";
    $openingHoursText    = $textData['openingHoursText']    ?? "Lundi - Vendredi : 9h00 - 18h00";
    $loadingText         = $textData['loadingText']         ?? "Envoi en cours...";
    $successTitleText    = $textData['successTitleText']    ?? "Message envoyé !";
    $successMessageText  = $textData['successMessageText']  ?? "Nous avons bien reçu votre message.<br>Nous vous répondrons dans les plus brefs délais.";
    $successEmailText    = $textData['successEmailText']    ?? "Un email de confirmation vous a été envoyé.";

?>




<section class="contact-section">
    <div class="container">
        <div class="contact-content">
            <div class="contact-info">
                <h2><?php echo $titleText; ?></h2>
                <p><?php echo $youhaveAQuestionText; ?></p>
                
                <div class="contact-details">
                    <div class="contact-item">
                        <h3>📧 <?php echo $emailText; ?></h3>
                        <p>contact@1ted.com</p>
                    </div>
                    <div class="contact-item">
                        <h3>📞 <?php echo $phoneText; ?></h3>
                        <p>+33 7 53 47 59 13</p>
                    </div>

                    <div class="contact-item">
                        <h3>🕒 <?php echo $hoursText; ?></h3>
                        <p><?php echo $openingHoursText; ?></p>
                    </div>
                </div>
            </div>

            <div class="contact-form-container" id="contactFormContainer">

                <!-- Loader (caché par défaut) -->
                <div id="contactLoader" style="display:none; text-align:center; padding: 60px 20px;">
                    <img src="/media/image/aienova-loader.gif" alt="Chargement..." class="loader invert">
                    <p style="margin-top: 16px; color: #555;"><?php echo $loadingText; ?></p>
                </div>

                <!-- Message de succès (caché par défaut) -->
                <div id="contactSuccess" style="display:none; text-align:center; padding: 40px 20px;">
                    <span style="font-size: 48px;">✅</span>
                    <h2 style="color: #27ae60; margin-top: 15px;"><?php echo $successTitleText; ?></h2>
                    <p><?php echo $successMessageText; ?></p>
                    <p style="margin-top: 20px; font-size: 13px; color: #777;"><?php echo $successEmailText; ?></p>
                </div>

                <!-- Message d'erreur (caché par défaut) -->
                <div id="contactError" style="display:none; background:#fdecea; border-left: 4px solid #e74c3c; padding: 12px 16px; margin-bottom: 20px; border-radius: 4px; color: #c0392b;"></div>

                <!-- Formulaire -->
                <form class="contact-form" id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName"><?php echo $firstNameText; ?> *</label>
                            <input type="text" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName"><?php echo $lastNameText; ?> *</label>
                            <input type="text" id="lastName" name="lastName" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email"><?php echo $emailText; ?> *</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone"><?php echo $phoneText; ?></label>
                        <input type="tel" id="phone" name="phone">
                    </div>

                    <div class="form-group">
                        <label for="subject"><?php echo $subjectText; ?> *</label>
                        <select id="subject" name="subject" required>
                            <option value=""><?php echo $selectSubjectText; ?></option>
                            <option value="general"><?php echo $generalQuestionText; ?></option>
                            <option value="support"><?php echo $technicalSupportText; ?></option>
                            <option value="partnership"><?php echo $partnershipText; ?></option>
                            <option value="other"><?php echo $otherText; ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message"><?php echo $messageText; ?> *</label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>

                    <!-- Captcha "Je ne suis pas un robot" -->
                    <div class="form-group captcha-group">
                        <label class="captcha-label">
                            <input type="checkbox" id="humanCheck" name="humanCheck">
                            <span class="captcha-box">
                                <span class="captcha-checkmark">✓</span>
                            </span>
                            <span class="captcha-text"><?php echo $captchaText; ?></span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit" id="contactSubmit" disabled><?php echo $sendMessageText; ?></button>
                </form>
            </div>

            <script src="/script/config.js"></script>
            <script src="/script/email/sendContactMail.js"></script>
        </div>
    </div>
</section>
