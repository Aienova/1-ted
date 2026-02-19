<?php

    class Preference {

        private $id;
        private $accountId; // ID du compte auquel cette préférence est associée
        private $keywords; // Mots-clés de recherche d'emploi ou candidat préférés

        private $maximumAge; // Âge maximum des candidats (optionnel)
        private $minimumExperience; // Expérience professionnelle minimale (optionnel)

        private $minimumSalary; // Salaire minimum souhaité (optionnel)

        private $maximumSalary; // Salaire maximum souhaité (optionnel)

        private $locations; // Localisation préférée (optionnel)

        private $suggestionActivated; // Indique si les suggestions de profils ou d'offres sont activées

        public function __construct($id, $accountId, $keywords, $maximumAge = null, $minimumExperience = null, $minimumSalary = null, $maximumSalary = null, $locations = null, $suggestionActivated = false) {
            $this->id = $id;
            $this->accountId = $accountId;
            $this->keywords = $keywords;
            $this->maximumAge = $maximumAge;
            $this->minimumExperience = $minimumExperience;
            $this->minimumSalary = $minimumSalary;
            $this->maximumSalary = $maximumSalary;
            $this->locations = $locations;
            $this->suggestionActivated = $suggestionActivated;
        }

        // Getters
        public function getId() {
            return $this->id;
        }
        public function getAccountId() {
            return $this->accountId;
        }

        public function getKeywords() {
            return $this->keywords;
        }

        public function getMaximumAge() {
            return $this->maximumAge;
        }

        public function getMinimumExperience() {
            return $this->minimumExperience;
        }

        public function getMinimumSalary() {
            return $this->minimumSalary;
        }

        public function getMaximumSalary() {
            return $this->maximumSalary;
        }

        public function getLocations() {
            return $this->locations;
        }

        public function isSuggestionActivated() {
            return $this->suggestionActivated;
        }

        // Setters

        public function setKeywords($keywords) {
            $this->keywords = $keywords;
        }

        public function setMaximumAge($maximumAge) {
            $this->maximumAge = $maximumAge;
        }

        public function setMinimumExperience($minimumExperience) {
            $this->minimumExperience = $minimumExperience;
        }

        public function setMinimumSalary($minimumSalary) {
            $this->minimumSalary = $minimumSalary;
        }

        public function setMaximumSalary($maximumSalary) {
            $this->maximumSalary = $maximumSalary;
        }

        public function setLocations($locations) {
            $this->locations = $locations;
        }

        public function setSuggestionActivated($suggestionActivated) {
            $this->suggestionActivated = $suggestionActivated;
        }

    }
