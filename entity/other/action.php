<?php

/*  


 Les actions possibles :

 Candidats :

- Demander des conseils ou avis à la communauté
- Envoyer un message direct à l'entreprise
- Propulser sa candidature en haut de la liste (recherche activement) 
- Afficher les informations complètes d'une offre d'emploi 
- Propulser sa candidature en haut de la liste (recherche Importante) 
- Propulser sa candidature en haut de la liste (recherche Urgente) 

Entreprises :

- Afficher les informations complètes d'un profil de candidat (Apprenti) 
- Afficher les informations complètes d'un profil de candidat (Junior) 
- Afficher les informations complètes d'un profil de candidat (Confirmé) 
- Afficher les informations complètes d'un profil de candidat (Senior) 
- Mettre en avant une offre d'emploi (Recherche active) 
- Mettre en avant une offre d'emploi (Recherche importante) 
- Mettre en avant une offre d'emploi (Recherche urgente) 


*/

class Action {
    public $id;
    public $name;
    public $description;
    public $isActive;

    public function __construct($id, $name, $description, $isActive) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->isActive = $isActive;
    }

    // GETTERS
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    // SETTERS
    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }
}