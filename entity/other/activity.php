<?php

class Activity {

    private $id;
    private $name;

    private $frName;
    private $enName;

    private $deName;

    private $esName;

    private $itName;

    private $nlName;

    private $ptName;


    private $description;

    public function __construct($id, $name, $frName = null, $enName = null, $deName = null, $esName = null, $itName = null, $nlName = null, $ptName = null, $description = null) {
        $this->id = $id;
        $this->name = $name;
        $this->frName = $frName;
        $this->enName = $enName;
        $this->deName = $deName;
        $this->esName = $esName;
        $this->itName = $itName;
        $this->nlName = $nlName;
        $this->ptName = $ptName;
        $this->description = $description;
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

    public function getFrName() {
        return $this->frName;
    }

    public function getEnName() {
        return $this->enName;
    }

    public function getDeName() {
        return $this->deName;
    }

    public function getEsName() {
        return $this->esName;
    }

    public function getItName() {
        return $this->itName;
    }

    public function getNlName() {
        return $this->nlName;
    }

    public function getPtName() {
        return $this->ptName;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    public function setName($name) {
        $this->name = $name;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    public function setFrName($frName) {
        $this->frName = $frName;
    }
    public function setEnName($enName) {
        $this->enName = $enName;
    }
    public function setDeName($deName) {
        $this->deName = $deName;
    }
    public function setEsName($esName) {
        $this->esName = $esName;
    }
    public function setItName($itName) {
        $this->itName = $itName;
    }
    public function setNlName($nlName) {
        $this->nlName = $nlName;
    }
    public function setPtName($ptName) {
        $this->ptName = $ptName;
    }

}