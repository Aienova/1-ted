<?php

class Company{

    private $id;
    private $name;

    private $customerNumber;

    private $nationRegisterNumber;

    private $description;

    private $domain;

    private $capacity;

    private $hqLocation;

    private $country;

    private $website;

    private $mediaCode;

    private $recruiting;

    private $contactId;

    private $banned; // Champ pour indiquer si l'entreprise est banni, false par défaut

    private $banlength; // Durée du bannissement en jours , null par défaut, mettre -1 pour bannissement à vie

    private $banEndDate; // Date de fin du bannissement au format "YYYY-MM-DD" (optionnel), null par défaut, mettre 9999-12-31 pour bannissement à vie

    private $banReason; // Raison du bannissement (optionnel), null par défaut

    private $dateCreation; // Date de création de l'entreprise au format "YYYY-MM-DD"



    public function __construct($id = null, $name = '') {
        $this->id = $id;
        $this->name = $name;
        $this->mediaCode = $this->generateUniqueMediaCode();
        $this->banned = false;
        $this->banlength = null;
        $this->banEndDate = null;
        $this->banReason = null;
        $this->dateCreation = null;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCustomerNumber() {
        return $this->customerNumber;
    }

    public function getNationRegisterNumber() {
        return $this->nationRegisterNumber;
    }
    
    public function getDomain() {
        return $this->domain;
    }   
    public function getCapacity() {
        return $this->capacity;
    }

    public function getHqLocation() {
        return $this->hqLocation;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getWebsite() {
        return $this->website;
    }

    public function getMediaCode() {
        return $this->mediaCode;
    }

    public function getRecruiting() {
        return $this->recruiting;
    }

    public function getContactId() {
        return $this->contactId;
    }

    public function isBanned() {
        return $this->banned;
    }

    public function getBanlength() {
        return $this->banlength;
    }

    public function getBanEndDate() {
        return $this->banEndDate;
    }

    public function getBanReason() {
        return $this->banReason;
    }

    public function getDateCreation() {
        return $this->dateCreation;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setCustomerNumber($customerNumber) {
        $this->customerNumber = $customerNumber;
    }

    public function setNationRegisterNumber($nationRegisterNumber) {
        $this->nationRegisterNumber = $nationRegisterNumber;
    }

    public function setDomain($domain) {
        $this->domain = $domain;
    }

    public function setCapacity($capacity) {
        $this->capacity = $capacity;
    }

    public function setHqLocation($hqLocation) {
        $this->hqLocation = $hqLocation;
    }
    public function setCountry($country) {
        $this->country = $country;
    }

    public function setWebsite($website) {
        $this->website = $website;
    }

    public function setMediaCode($mediaCode = null) {
        $this->mediaCode = $mediaCode ?: $this->generateUniqueMediaCode();
    }

    public function setRecruiting($recruiting) {
        $this->recruiting = $recruiting;
    }

    public function setContactId($contactId) {
        $this->contactId = $contactId;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setBanned($banned) {
        $this->banned = $banned ?? false;
    }

    public function setBanlength($banlength) {
        $this->banlength = $banlength;
    }

    public function setBanEndDate($banEndDate) {
        $this->banEndDate = $banEndDate;
    }

    public function setBanReason($banReason) {
        $this->banReason = $banReason;
    }

    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;
    }

    /**
     * Génère un code média unique de 12 caractères
     * Mélange des lettres aléatoires avec des chiffres extraits de la date/heure de création
     * @return string Code unique de 12 caractères
     */
    private function generateUniqueMediaCode() {
        // Extraire tous les chiffres de la date/heure de création (YmdHis)
        $timestamp = date('YmdHis'); // Ex: 20260203143052
        $digits = str_split($timestamp); // Tableau de chiffres
        
        // Générer des lettres aléatoires (majuscules et minuscules)
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomLetters = [];
        for ($i = 0; $i < 6; $i++) {
            $randomLetters[] = $letters[random_int(0, strlen($letters) - 1)];
        }
        
        // Sélectionner 6 chiffres aléatoires du timestamp
        shuffle($digits);
        $selectedDigits = array_slice($digits, 0, 6);
        
        // Mélanger les lettres et les chiffres
        $characters = array_merge($randomLetters, $selectedDigits);
        shuffle($characters);
        
        // Prendre les 12 premiers caractères pour garantir la longueur
        $mediaCode = implode('', array_slice($characters, 0, 12));
        
        return $mediaCode;
    }
}