<?php

class Job {
    private $id;
    private $title;
    private $description;
    private $location;

    private $activity; // ID de l'activité associée à ce poste (optionnel)

    private $country; // ID du pays associé à ce poste (optionnel)
    private $salary;
    private $contractType;
    private $startDate;
    private $length;
    private $companyId;
    private $workCondition;
    private $needVehicle;
    private $contactId;
    private $requirementId;
    private $open;

    private $approved;

    public function __construct(
        $id = null,
        $title = '',
        $description = '',
        $location = null,
        $activity = null,
        $country = null,
        $salary = null,
        $contractType = null,
        $startDate = null,
        $length = null,
        $companyId = null,
        $workCondition = null,
        $needVehicle = null,
        $contactId = null,
        $requirementId = null,
        $open = null,
        $approved = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
        $this->salary = $salary;
        $this->contractType = $contractType;
        $this->startDate = $startDate;
        $this->length = $length;
        $this->companyId = $companyId;
        $this->workCondition = $workCondition;
        $this->needVehicle = $needVehicle;
        $this->contactId = $contactId;
        $this->requirementId = $requirementId;
        $this->open = $open;
        $this->approved = $approved;
        $this->activity = $activity;
        $this->country = $country;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getLocation() { return $this->location; }
    public function getSalary() { return $this->salary; }
    public function getContractType() { return $this->contractType; }
    public function getStartDate() { return $this->startDate; }
    public function getLength() { return $this->length; }
    public function getCompanyId() { return $this->companyId; }
    public function getWorkCondition() { return $this->workCondition; }
    public function getNeedVehicle() { return $this->needVehicle; }
    public function getContactId() { return $this->contactId; }
    public function getRequirementId() { return $this->requirementId; }
    public function getActivity() { return $this->activity; }
    public function getCountry() { return $this->country; }
    public function getOpen() { return $this->open; }

    public function isApproved() { return $this->approved; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setTitle($title) { $this->title = $title; }
    public function setDescription($description) { $this->description = $description; }
    public function setLocation($location) { $this->location = $location; }
    public function setSalary($salary) { $this->salary = $salary; }
    public function setContractType($contractType) { $this->contractType = $contractType; }
    public function setStartDate($startDate) { $this->startDate = $startDate; }
    public function setLength($length) { $this->length = $length; }
    public function setCompanyId($companyId) { $this->companyId = $companyId; }
    public function setWorkCondition($workCondition) { $this->workCondition = $workCondition; }
    public function setNeedVehicle($needVehicle) { $this->needVehicle = $needVehicle; }
    public function setContactId($contactId) { $this->contactId = $contactId; }
    public function setRequirementId($requirementId) { $this->requirementId = $requirementId; }
    public function setOpen($open) { $this->open = $open; }
    public function setActivity($activity) { $this->activity = $activity; }
    public function setCountry($country) { $this->country = $country; }

    public function setApproved($approved) { $this->approved = $approved; }
}

?>