<?php

class Social {

    private $id;
    private $linkedin;
    private $twitter;
    private $github;
    private $website;
    private $instagram;
    private $behance;
    private $facebook;
    private $other;
    
    public function __construct($id, $linkedin = '', $twitter = '', $github = '', $website = '', $instagram = '', $behance = '', $facebook = '', $other = '') {
        $this->id = $id;
        $this->linkedin = $linkedin;
        $this->twitter = $twitter;
        $this->github = $github;
        $this->website = $website;
        $this->instagram = $instagram;
        $this->behance = $behance;
        $this->facebook = $facebook;
        $this->other = $other;
    }
    
    // Getters

    public function getId() {   
        return $this->id;
    }
    
    public function getLinkedin() {
        return $this->linkedin;
    }
    
    public function getTwitter() {
        return $this->twitter;
    }
    
    public function getGithub() {
        return $this->github;
    }
    
    public function getWebsite() {
        return $this->website;
    }
    
    public function getInstagram() {
        return $this->instagram;
    }
    
    public function getBehance() {
        return $this->behance;
    }
    
    public function getFacebook() {
        return $this->facebook;
    }
    
    public function getOther() {
        return $this->other;
    }
    
    // Setters
    public function setLinkedin($linkedin) {
        $this->linkedin = $linkedin;
    }
    
    public function setTwitter($twitter) {
        $this->twitter = $twitter;
    }
    
    public function setGithub($github) {
        $this->github = $github;
    }
    
    public function setWebsite($website) {
        $this->website = $website;
    }
    
    public function setInstagram($instagram) {
        $this->instagram = $instagram;
    }
    
    public function setBehance($behance) {
        $this->behance = $behance;
    }
    
    public function setFacebook($facebook) {
        $this->facebook = $facebook;
    }
    
    public function setOther($other) {
        $this->other = $other;
    }
    
    // Méthode pour obtenir tous les réseaux sociaux
    public function getAllSocialLinks() {
        return [
            'linkedin' => $this->linkedin,
            'twitter' => $this->twitter,
            'github' => $this->github,
            'website' => $this->website,
            'instagram' => $this->instagram,
            'behance' => $this->behance,
            'facebook' => $this->facebook,
            'other' => $this->other
        ];
    }
    
    // Méthode pour vérifier si au moins un réseau social est défini
    public function hasSocialLinks() {
        return !empty($this->linkedin) || !empty($this->twitter) || 
               !empty($this->github) || !empty($this->website) ||
               !empty($this->instagram) || !empty($this->behance) ||
               !empty($this->facebook) || !empty($this->other);
    }
}
