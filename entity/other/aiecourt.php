<?php

/* Le AIEcourt correspond aux prix de chaque action fait sur le site 1-ted sur les aiecoins, les prix en aiecoins 
varient en fonction du pays où se situe l'entreprise, du secteur d'activité, de l'action effectué et du role 
de l'utilisateur (candidat ou entreprise). Chaque pays n'ont pas les valeurs de prix en aiecoins pour chaque action, 
secteur d'activité et role, il y a des pays qui n'ont pas de prix pour certaines actions, secteurs d'activité ou role.



Les prix de bases par actions et rôles sont les suivants :

Candidats :

- Demander des conseils ou avis à la communauté : 5 AIECoins
- Envoyer un message direct à l'entreprise : 10 AIECoins
- Propulser sa candidature en haut de la liste (recherche activement) : 20 AIECoins
- Afficher les informations complètes d'une offre d'emploi : 30 AIECoins
- Propulser sa candidature en haut de la liste (recherche Importante) : 40 AIECoins
- Propulser sa candidature en haut de la liste (recherche Urgente) : 60 AIECoins

Entreprises :

- Afficher les informations complètes d'un profil de candidat (Apprenti) : 5 AIECoins
- Afficher les informations complètes d'un profil de candidat (Junior) : 10 AIECoins
- Afficher les informations complètes d'un profil de candidat (Confirmé) : 20 AIECoins
- Afficher les informations complètes d'un profil de candidat (Senior) : 40 AIECoins
- Mettre en avant une offre d'emploi (Recherche active) : 20 AIECoins
- Mettre en avant une offre d'emploi (Recherche importante) : 40 AIECoins
- Mettre en avant une offre d'emploi (Recherche urgente) : 60 AIECoins


la valeur de base de l'aiecoins est de 1€ = 25 AIECoins, (les prix en aiecoins peuvent varier en fonction du pays, du secteur d'activité et du role de l'utilisateur)

Les secteurs d'activités ayant le plus de demandes auront un prix plus élevé en aiecoins, les secteurs d'activités ayant le moins de demandes auront un prix plus bas en aiecoins, les secteurs d'activités n'ayant aucune demande n'auront pas de prix en aiecoins.


*/

    class AIECourt {

        public $id;
        public $activity; // Secteur d'activité (en fonction du role) objet de la classe Activity qui contient les informations du secteur d'activité (id, name)
        public $country; // Pays de l'entreprise ou du candidat (en fonction du role) objet de la classe Country qui contient les informations du pays (id, name, code)
        public $action; // Actions , c'est un objet de la classe Action qui contient les informations de l'action (id, name, description, isActive)
        public $price; // Prix en AIECoins pour cette action
        public $role; // Rôle de l'utilisateur (candidat ou entreprise)

        public function __construct($id, $activity, $country, $action, $price, $role) {
            $this->id = $id;
            $this->activity = $activity;
            $this->country = $country;
            $this->action = $action;
            $this->price = $price;
            $this->role = $role;
        }

        // GETTERS //
        public function getId() {
            return $this->id;
        }
        public function getActivity() {
            return $this->activity;
        }
        public function getCountry() {
            return $this->country;
        }
        public function getAction() {
            return $this->action;
        }
        public function getPrice() {
            return $this->price;
        }
        public function getRole() {
            return $this->role;
        }

        // SETTERS //
        public function setId($id) {
            $this->id = $id;
        }

        public function setActivity($activity) {
            $this->activity = $activity;
        }

        public function setCountry($country) {
            $this->country = $country;
        }

        public function setAction($action) {
            $this->action = $action;
        }

        public function setPrice($price) {
            $this->price = $price;
        }

        public function setRole($role) {
            $this->role = $role;
        }


    }