<?php

class Skill {

    private $id;
    private $title;
    private $year;
    private $favorite;

    public function __construct($id = null, $title = '', $year = '', $favorite = false) {
        $this->id = $id;
        $this->title = $title;
        $this->year = $year;
        $this->favorite = $favorite;
    }
    // Getters
    public function getId() {
        return $this->id;
    }   

    public function getTitle() {
        return $this->title;
    }
    public function getYear() {
        return $this->year;
    }
    public function getFavorite() {
        return $this->favorite;
    }
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    public function setTitle($title) {
        $this->title = $title;
    }
    public function setYear($year) {
        $this->year = $year;
    }
    public function setFavorite($favorite) {
        $this->favorite = $favorite;
    }
}