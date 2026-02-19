<?php

require_once __DIR__ . '/../../function/getData.php';

class Formation {

    private $id;
    private $startDate;
    private $endDate;

    private $current;
    private $title;
    private $school;
    private $location;
    private $knowledge;
    
    public function __construct($id, $startDate, $endDate, $current, $title, $school = '', $location = '', $knowledge = []) {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->current = $current;
        $this->title = $title;
        $this->school = $school;
        $this->location = $location;
        // Décoder JSON si c'est une chaîne
        if (is_string($knowledge)) {
            $this->knowledge = json_decode($knowledge, true) ?: [];
        } else {
            $this->knowledge = $knowledge;
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
    
    public function getTitle() {
        return $this->title;
    }
    
    public function getSchool() {
        return $this->school;
    }
    
    public function getLocation() {
        return $this->location;
    }
    
    public function getKnowledge() {
        return $this->knowledge;
    }
    
    // Setters
    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }
    
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    public function setCurrent($current) {
        $this->current = $current;
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function setSchool($school) {
        $this->school = $school;
    }
    
    public function setLocation($location) {
        $this->location = $location;
    }
    
    public function setKnowledge($knowledge) {
        $this->knowledge = $knowledge;
    }
    
    // Method to add knowledge
    public function addKnowledge($knowledgeItem) {
        $this->knowledge[] = $knowledgeItem;
    }
    
    /**
     * Get all Knowledge objects from database
     * @return array Array of Knowledge objects
     */
    public function getKnowledges() {
        if (empty($this->knowledge)) {
            return [];
        }
        
        $knowledges = [];
        foreach ($this->knowledge as $knowledgeId) {
            $knowledgeObj = getData('knowledge', $knowledgeId, true);
            if ($knowledgeObj !== null) {
                $knowledges[] = $knowledgeObj;
            }
        }
        return $knowledges;
    }
    
    // Method to format the period
    public function getPeriod() {
        return $this->startDate . ' - ' . $this->endDate;
    }
}
