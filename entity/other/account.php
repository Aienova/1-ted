<?php

    class Account {

        private $id;

        private $firstname;
        private $lastname;

        private $login; // Identifiant de connexion (email ou nom d'utilisateur)

        private $telephone; // Numéro de téléphone (optionnel)

        private $companyName; // Nom de l'entreprise (pour les recruteurs)
        private $companySiret; // SIRET de l'entreprise (pour les recruteurs)

        private $activity; // Secteur d'activité (pour les recruteurs et candidats)

        private $email;
        private $passwordHash;
        private $role;
        private $createdAt;

        private $country; // Pays de résidence (optionnel)

        private $lastconnection; // Date de la dernière mise à jour du compte (optionnel)

        private $candidateId; // ID du candidat associé à ce compte (pour les candidats)
        private $companyId; // ID du recruteur associé à ce compte (pour l'entreprise)
        private $preference; // Objet de préférences de recherche (optionnel)

        private $forgetCode; // Code de réinitialisation du mot de passe (optionnel)

        private $token; // Token de session ou d'authentification

        private $verificationCode; // Code de vérification pour la validation de l'email (optionnel)

        private $verificationExpireDate; // Date d'expiration du code de vérification (optionnel)

        private $verified; // Indique si l'email a été vérifié (booléen)

        private $aiecoins; // Solde d'AIECoins du compte (optionnel)

        public function __construct($id, $firstname, $lastname, $login, $telephone, $companyName, $companySiret, $activity, $email, $passwordHash, $role, $createdAt, $country = null, $lastconnection = null, $token = null, $candidateId = null, $companyId = null, $preference = null, $forgetCode = null, $verificationCode = null, $verificationExpireDate = null, $verified = false, $aiecoins = null) {
            
            $this->id = $id;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->login = $login; // Utiliser l'email comme login
            $this->telephone = $telephone;
            $this->companyName = $companyName;
            $this->companySiret = $companySiret;
            $this->activity = $activity;
            $this->email = $email;
            $this->passwordHash = $passwordHash;
            $this->role = $role;
            $this->createdAt = $createdAt;
            $this->country = $country;
            $this->lastconnection = $lastconnection;
            $this->token = $token;
            $this->candidateId = $candidateId;
            $this->companyId = $companyId;
            $this->preference = $preference;
            $this->forgetCode = $forgetCode;
            $this->verificationCode = $verificationCode;
            $this->verificationExpireDate = $verificationExpireDate;
            $this->verified = $verified;
            $this->aiecoins = $aiecoins;
        }

        // Getters
        public function getId() {
            return $this->id;
        }

        public function getFirstname() {
            return $this->firstname;
        }

        public function getLastname() {
            return $this->lastname;
        }

        public function getLogin() {
            return $this->login;
        }

        public function getTelephone() {
            return $this->telephone;
        }

        public function getCompanyName() {
            return $this->companyName;
        }

        public function getCompanySiret() {
            return $this->companySiret;
        }

        public function getActivity() {
            return $this->activity;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getPasswordHash() {
            return $this->passwordHash;
        }

        public function getRole() {
            return $this->role;
        }

        public function getCreatedAt() {
            return $this->createdAt;
        }

        public function getLastconnection() {
            return $this->lastconnection;
        }

        public function getToken() {
            return $this->token;
        }

        public function getCandidateId() {
            return $this->candidateId;
        }

        public function getCountry() {
            return $this->country;
        }

        public function getCompanyId() {
            return $this->companyId;
        }

        public function getPreference() {
            return $this->preference;
        }

        public function getForgetCode() {
            return $this->forgetCode;
        }

        public function getAiecoins() {
            return $this->aiecoins;
        }

        public function getVerificationCode() {
            return $this->verificationCode;
        }

        public function getVerificationExpireDate() {
            return $this->verificationExpireDate;
        }

        public function isVerified() {
            return $this->verified;
        }

        // Setters
        public function setEmail($email) {
            $this->email = $email;
        }

        public function setFirstname($firstname) {
            $this->firstname = $firstname;
        }

        public function setLastname($lastname) {
            $this->lastname = $lastname;
        }

        public function setLogin($login) {
            $this->login = $login;
        }

        public function setTelephone($telephone) {
            $this->telephone = $telephone;
        }

        public function setCompanyName($companyName) {
            $this->companyName = $companyName;
        }

        public function setCompanySiret($companySiret) {
            $this->companySiret = $companySiret;
        }

        public function setActivity($activity) {
            $this->activity = $activity;
        }

        public function setPasswordHash($passwordHash) {
            $this->passwordHash = $passwordHash;
        }

        public function setRole($role) {
            $this->role = $role;
        }

        public function setCreatedAt($createdAt) {
            $this->createdAt = $createdAt;
        }

        public function setLastconnection($lastconnection) {
            $this->lastconnection = $lastconnection;
        }

        public function setCountry($country) {
            $this->country = $country;
        }

        public function setToken($token) {
            $this->token = $token;
        }

        public function setCandidateId($candidateId) {
            $this->candidateId = $candidateId;
        }

        public function setCompanyId($companyId) {
            $this->companyId = $companyId;
        }

        public function setPreference($preference) {
            $this->preference = $preference;
        }

        public function setForgetCode($forgetCode) {
            $this->forgetCode = $forgetCode;
        }

        public function setAiecoins($aiecoins) {
            $this->aiecoins = $aiecoins;
        }

        public function setVerificationCode($verificationCode) {
            $this->verificationCode = $verificationCode;
        }
        public function setVerificationExpireDate($verificationExpireDate) {
            $this->verificationExpireDate = $verificationExpireDate;
        }

        public function setVerified($verified) {
            $this->verified = $verified;
        }
    }