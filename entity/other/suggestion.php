<?php

class Suggestion{

private $id;
private $jobId;

private $candidateId;

private $companyId;






public function __construct($id = null, $jobId = null, $candidateId = null, $companyId = null) {
    $this->id = $id;
    $this->jobId = $jobId;
    $this->candidateId = $candidateId;
    $this->companyId = $companyId;


}

// Getters
public function getId() {
    return $this->id;
}

public function getJobId() {
    return $this->jobId;
}

public function getCandidateId() {
    return $this->candidateId;
}

public function getCompanyId() {
    return $this->companyId;
}



// Setters
public function setId($id) {
    $this->id = $id;
}

public function setJobId($jobId) {
    $this->jobId = $jobId;
}

public function setCandidateId($candidateId) {
    $this->candidateId = $candidateId;
}

public function setCompanyId($companyId) {
    $this->companyId = $companyId;
}




    
}
