<?php

class Country{

    private $id;
    private $name;

    private $code; // Code ISO du pays (optionnel)

    private $flagUrl; // URL du drapeau du pays (optionnel)

    private $defaultLanguage; // Langue par défaut du pays (optionnel)

    private $currencySymbol; // Devise utilisée dans le pays (optionnel)

    private $frName;
    private $enName;

    private $deName;

    private $esName;

    private $itName;

    private $nlName;

    private $ptName;

    public function __construct($id, $name, $code = null, $flagUrl = null, $defaultLanguage = null, $currencySymbol = null, $frName = null, $enName = null, $deName = null, $esName = null, $itName = null, $nlName = null, $ptName = null) {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
        $this->flagUrl = $flagUrl;
        $this->defaultLanguage = $defaultLanguage;
        $this->currencySymbol = $currencySymbol;
        $this->frName = $frName;
        $this->enName = $enName;
        $this->deName = $deName;
        $this->esName = $esName;
        $this->itName = $itName;
        $this->nlName = $nlName;
        $this->ptName = $ptName;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCode() {
        return $this->code;
    }

    public function getFlagUrl() {
        return $this->flagUrl;
    }

    public function getDefaultLanguage() {
        return $this->defaultLanguage;
    }

    public function getCurrencySymbol() {
        return $this->currencySymbol;
    }

    public function getFrName() {
        return $this->frName;
    }

    public function getEnName() {
        return $this->enName;
    }

    public function getDeName() {
        return $this->deName;
    }

    public function getEsName() {
        return $this->esName;
    }

    public function getItName() {
        return $this->itName;
    }

    public function getNlName() {
        return $this->nlName;
    }

    public function getPtName() {
        return $this->ptName;
    }

    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function setFlagUrl($flagUrl) {
        $this->flagUrl = $flagUrl;
    }

    public function setDefaultLanguage($defaultLanguage) {
        $this->defaultLanguage = $defaultLanguage;
    }

    public function setCurrencySymbol($currencySymbol) {
        $this->currencySymbol = $currencySymbol;
    }

    public function setFrName($frName) {
        $this->frName = $frName;
    }

    public function setEnName($enName) {
        $this->enName = $enName;
    }

    public function setDeName($deName) {
        $this->deName = $deName;
    }

    public function setEsName($esName) {
        $this->esName = $esName;
    }

    public function setItName($itName) {
        $this->itName = $itName;
    }

    public function setNlName($nlName) {
        $this->nlName = $nlName;
    }

    public function setPtName($ptName) {
        $this->ptName = $ptName;
    }


}