<?php

require_once __DIR__ . '/../../function/getData.php';

class Experience {

    private $id;    
    private $startDate;
    private $endDate;
    private $current;
    private $position;
    private $company;
    private $location;
    private $missions;
    
    public function __construct($id, $startDate, $endDate, $current, $position, $company = '', $location = '', $missions = []) {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->current = $current;
        $this->position = $position;
        $this->company = $company;
        $this->location = $location;
        // Décoder JSON si c'est une chaîne
        if (is_string($missions)) {
            $this->missions = json_decode($missions, true) ?: [];
        } else {
            $this->missions = $missions;
        }
    }
    
    // Getters

    public function getId() {
        return $this->id;
    }   
    public function getStartDate() {
        return $this->startDate;
    }
    
    public function getEndDate() {
        return $this->endDate;
    }
    
    public function isCurrent() {
        return $this->current;
    }

    public function getPosition() {
        return $this->position;
    }
    
    public function getCompany() {
        return $this->company;
    }
    
    public function getLocation() {
        return $this->location;
    }
    
    public function getMissionIds() {
        return $this->missions;
    }
    
    /**
     * Get all Mission objects from database
     * @return array Array of Mission objects
     */
    public function getMissions() {
        if (empty($this->missions)) {
            return [];
        }
        
        $missionObjects = [];
        foreach ($this->missions as $missionId) {
            $mission = getData('mission', $missionId, true);
            if ($mission !== null) {
                $missionObjects[] = $mission;
            }
        }
        return $missionObjects;
    }
    
    // Setters
    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }
    
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }
    
    public function setPosition($position) {
        $this->position = $position;
    }
    
    public function setCompany($company) {
        $this->company = $company;
    }
    
    public function setLocation($location) {
        $this->location = $location;
    }

    public function setCurrent($current) {
        $this->current = $current;
    }
    
    public function setMissions($missions) {
        $this->missions = $missions;
    }
    
    // Method to add a mission
    public function addMission($mission) {
        $this->missions[] = $mission;
    }
    
    // Method to format the period
    public function getPeriod() {
        return $this->startDate . ' - ' . $this->endDate;
    }
}
