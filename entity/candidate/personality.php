<?php

class Personality {

    private $id;
    private $trait;
    
    public function __construct($id = null, $trait = '') {
        $this->id = $id;
        $this->trait = $trait;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getTrait() {
        return $this->trait;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setTrait($trait) {
        $this->trait = $trait;
    }
}