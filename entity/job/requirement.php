<?php


class Requirement {

    private $id;
    private $degree;
    private $experienceYears;
    private $description;

    public function __construct($id = null, $description = '', $degree = null, $experienceYears = null) {
        $this->id = $id;
        $this->description = $description;
        $this->degree = $degree;
        $this->experienceYears = $experienceYears;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDegree() {
        return $this->degree;
    }

    public function getExperienceYears() {
        return $this->experienceYears;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setDegree($degree) {
        $this->degree = $degree;
    }

    public function setExperienceYears($experienceYears) {
        $this->experienceYears = $experienceYears;
    }
}