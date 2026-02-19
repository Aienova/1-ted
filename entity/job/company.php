<?php

class Company{

    private $id;
    private $name;

    private $customerNumber;

    private $nationRegisterNumber;

    private $description;

    private $domain;

    private $capacity;

    private $hqLocation;

    private $country;

    private $recruiting;

    private $contactId;



    public function __construct($id = null, $name = '') {
        $this->id = $id;
        $this->name = $name;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCustomerNumber() {
        return $this->customerNumber;
    }

    public function getNationRegisterNumber() {
        return $this->nationRegisterNumber;
    }
    
    public function getDomain() {
        return $this->domain;
    }   
    public function getCapacity() {
        return $this->capacity;
    }

    public function getHqLocation() {
        return $this->hqLocation;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getRecruiting() {
        return $this->recruiting;
    }

    public function getContactId() {
        return $this->contactId;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setCustomerNumber($customerNumber) {
        $this->customerNumber = $customerNumber;
    }

    public function setNationRegisterNumber($nationRegisterNumber) {
        $this->nationRegisterNumber = $nationRegisterNumber;
    }

    public function setDomain($domain) {
        $this->domain = $domain;
    }

    public function setCapacity($capacity) {
        $this->capacity = $capacity;
    }

    public function setHqLocation($hqLocation) {
        $this->hqLocation = $hqLocation;
    }
    public function setCountry($country) {
        $this->country = $country;
    }

    public function setRecruiting($recruiting) {
        $this->recruiting = $recruiting;
    }

    public function setContactId($contactId) {
        $this->contactId = $contactId;
    }

    public function setName($name) {
        $this->name = $name;
    }
}