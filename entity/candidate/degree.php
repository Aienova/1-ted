<?php

class Degree {

    private $id;
    private $title;
    
    public function __construct($id = null, $title = '') {
        $this->id = $id;
        $this->title = $title;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
}