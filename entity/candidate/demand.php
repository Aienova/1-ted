<?php

class Demand {
    private $id;
    private $contractType;
    private $salary;
    private $workCondition;
    
    public function __construct($id = null, $contractType = '', $salary = 0, $workCondition = '') {
        $this->id = $id;
        $this->contractType = $contractType;
        $this->salary = $salary;
        $this->workCondition = $workCondition;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getContractType() {
        return $this->contractType;
    }
    
    public function getSalary() {
        return $this->salary;
    }
    
    public function getWorkCondition() {
        return $this->workCondition;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setContractType($contractType) {
        $this->contractType = $contractType;
    }
    
    public function setSalary($salary) {
        $this->salary = $salary;
    }
    
    public function setWorkCondition($workCondition) {
        $this->workCondition = $workCondition;
    }
    
    // Helper method to get formatted salary
    public function getFormattedSalary() {
        return number_format($this->salary, 2, ',', ' ') . ' € Net';
    }
}
