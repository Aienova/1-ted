<?php

require_once __DIR__ . '/../../function/getData.php';

class Identity {

    private $id;    
    private $lastName;
    private $firstName;

    private $birthdate; 
    
    private $degree; // Degree object
    private $jobTitle;
    private $phone;

    private $location;
    private $email;

    private $vehicule;
    private $description;
    private $languageIds;
    private $hobbieIds;
    
    public function __construct($id, $lastName, $firstName, $birthdate, $degree, $jobTitle, $phone, $location, $email, $vehicule, $description, $languageIds = [], $hobbieIds = []) {
        $this->id = $id;
        $this->lastName = $lastName;
        $this->firstName = $firstName;  
        $this->birthdate = $birthdate;
        $this->degree = $degree; // Degree object or null
        $this->jobTitle = $jobTitle;
        $this->phone = $phone;
        $this->location = $location;
        $this->email = $email;
        $this->vehicule = $vehicule;
        $this->description = $description;
        $this->languageIds = $languageIds;
        $this->hobbieIds = $hobbieIds;
    }
    
    // Getters

    public function getId() {
        return $this->id;
    }
    public function getLastName() {
        return $this->lastName;
    }
    
    public function getFirstName() {
        return $this->firstName;
    }

    public function getBirthdate() {
        return $this->birthdate;
    }  

    public function getDegree() {
        return $this->degree; // Returns Degree object
    }

    public function setDegree($degree) {
        $this->degree = $degree; // Set Degree object
    }

    public function getJobTitle() {
        return $this->jobTitle;
    }
    

    public function getLocation() {
        return $this->location;
    }



    public function getPhone() {
        return $this->phone;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function isVehicule() {
        return $this->vehicule;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function getLanguageIds() {
        return $this->languageIds;
    }
    
    public function getHobbieIds() {
        return $this->hobbieIds;
    }
    
    // Setters
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }
    
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLocation($location) {
        $this->location = $location;
    }
    
    public function setPhone($phone) {
        $this->phone = $phone;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }

        public function setJobTitle($jobTitle) {
        $this->jobTitle = $jobTitle;
    }

    
    public function setLanguageIds($languageIds) {
        $this->languageIds = $languageIds;
    }
    
    public function setHobbieIds($hobbieIds) {
        $this->hobbieIds = $hobbieIds;
    }

    public function setVehicule($vehicule) {
        $this->vehicule = $vehicule;
    }
    
    // Methods to manage language IDs
    public function addLanguageId($languageId) {
        if (!in_array($languageId, $this->languageIds)) {
            $this->languageIds[] = $languageId;
        }
    }
    
    public function removeLanguageId($languageId) {
        $key = array_search($languageId, $this->languageIds);
        if ($key !== false) {
            unset($this->languageIds[$key]);
            $this->languageIds = array_values($this->languageIds);
        }
    }
    
    public function addHobbieId($hobbieId) {
        if (!in_array($hobbieId, $this->hobbieIds)) {
            $this->hobbieIds[] = $hobbieId;
        }
    }
    
    public function removeHobbieId($hobbieId) {
        $key = array_search($hobbieId, $this->hobbieIds);
        if ($key !== false) {
            unset($this->hobbieIds[$key]);
            $this->hobbieIds = array_values($this->hobbieIds);
        }
    }
    
    /**
     * Get all Language objects from database
     * @return array Array of Language objects
     */
    public function getLanguages() {
        if (empty($this->languageIds)) {
            return [];
        }
        
        $languages = [];
        foreach ($this->languageIds as $langId) {
            $language = getData('language', $langId, true);
            if ($language !== null) {
                $languages[] = $language;
            }
        }
        return $languages;
    }
    
    /**
     * Get all Hobbie objects from database
     * @return array Array of Hobbie objects
     */
    public function getHobbies() {
        if (empty($this->hobbieIds)) {
            return [];
        }
        
        $hobbies = [];
        foreach ($this->hobbieIds as $hobbieId) {
            $hobbie = getData('hobbie', $hobbieId, true);
            if ($hobbie !== null) {
                $hobbies[] = $hobbie;
            }
        }
        return $hobbies;
    }
    
    public function setDescription($description) {
        $this->description = $description;
    }
}
