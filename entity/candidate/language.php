<?php

class Language {
    private $id;
    private $languageName;
    private $level; // LanguageLevel object
    private $isNative;
    
    public function __construct($id = null, $languageName = '', $level = null, $isNative = false) {
        $this->id = $id;
        $this->languageName = $languageName;
        $this->level = $level; // LanguageLevel object or null
        $this->isNative = $isNative;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getLanguageName() {
        return $this->languageName;
    }
    
    public function getLevel() {
        return $this->level; // Returns LanguageLevel object
    }
    
    public function getIsNative() {
        return $this->isNative;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setLanguageName($languageName) {
        $this->languageName = $languageName;
    }
    
    public function setLevel($level) {
        $this->level = $level;
    }
    
    public function setIsNative($isNative) {
        $this->isNative = $isNative;
    }
}
