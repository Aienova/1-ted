<?php

class Application{

    private $id;
    private $candidateId;
    private $jobId;
    private $status;

    private $recruiterValidation;

    private $candidateValidation;
    private $comment;
    
    // Relations
    private $candidate;  // Candidate object
    private $job;        // Job object
    
    public function __construct($id = null, $candidateId = null, $jobId = null, $status = '', $recruiterValidation = false, $candidateValidation = false, $comment = '') {
        $this->id = $id;
        $this->candidateId = $candidateId;
        $this->jobId = $jobId;
        $this->status = $status;
        $this->recruiterValidation = $recruiterValidation;
        $this->candidateValidation = $candidateValidation;
        $this->comment = $comment;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getCandidateId() {
        return $this->candidateId;
    }

    public function getComment() {
        return $this->comment;
    }

    public function isRecruiterValidation() {
        return $this->recruiterValidation;
    }

    public function isCandidateValidation() {
        return $this->candidateValidation;
    }

    public function getJobId() {
        return $this->jobId;
    }
    
    // Getters pour les relations
    public function getCandidate() {
        return $this->candidate;
    }
    
    public function getJob() {
        return $this->job;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setCandidateId($candidateId) {
        $this->candidateId = $candidateId;
    }
    
    public function setJobId($jobId) {
        $this->jobId = $jobId;
    }
    
    public function setStatus($status) {
        $this->status = $status;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function setRecruiterValidation($recruiterValidation) {
        $this->recruiterValidation = $recruiterValidation;
    }

    public function setCandidateValidation($candidateValidation) {
        $this->candidateValidation = $candidateValidation;
    }
    
    // Setters pour les relations
    public function setCandidate($candidate) {
        $this->candidate = $candidate;
        if ($candidate !== null && method_exists($candidate, 'getId')) {
            $this->candidateId = $candidate->getId();
        }
    }
    
    public function setJob($job) {
        $this->job = $job;
        if ($job !== null && method_exists($job, 'getId')) {
            $this->jobId = $job->getId();
        }
    }
}