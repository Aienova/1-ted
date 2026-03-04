<?php

class Job {
    private $id;
    private $title;
    private $description;
    private $location;

    private $activity; // Objet Activity associé à ce poste (optionnel, lié via fk_job_activity)

    private $country;  // Objet Country associé à ce poste (optionnel, lié via fk_job_country)
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

    private $priority; // Champ de priorité du niveau de priorité du poste, 0 par défaut

    private $startDatePriority; // Champ pour indiquer la date de début de la priorité du candidat, null par défaut, format "YYYY-MM-DD"

    private $endDatePriority; // Champ pour indiquer la date de fin de la priorité du candidat, null par défaut, format "YYYY-MM-DD"

    private $lengthPriority; // Champ de priorité de la durée du poste, 0 par défaut
    
    private $createdAt;
    private $updatedAt;

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
        $this->priority = 0;
        $this->startDatePriority = null;
        $this->endDatePriority = null;
        $this->lengthPriority = 0;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
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
    public function getPriority() { return $this->priority; }
    public function getStartDatePriority() { return $this->startDatePriority; }
    public function getEndDatePriority() { return $this->endDatePriority; }
    public function getLengthPriority() { return $this->lengthPriority; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }

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
    public function setPriority($priority) { $this->priority = $priority ?? 0; }
    public function setStartDatePriority($startDatePriority) { $this->startDatePriority = $startDatePriority; }
    public function setEndDatePriority($endDatePriority) { $this->endDatePriority = $endDatePriority; }
    public function setLengthPriority($lengthPriority) { $this->lengthPriority = $lengthPriority ?? 0; }
    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
    public function setUpdatedAt($updatedAt) { $this->updatedAt = $updatedAt; }
}

?>