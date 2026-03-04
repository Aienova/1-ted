<?php

    class Exchange {

        public $id; // ID de l'échange
        public $activity; // Activité liée à l'échange, c'est un objet de la classe Activity qui contient les informations de l'activité (id, name, description)
        public $country; // Pays lié à l'échange, c'est un objet de la classe Country qui contient les informations du pays (id, name, code)
        public $currencyCode; // Code de la devise utilisée pour l'échange mettre les devises suivantes : EUR, USD, GBP, JPY, AUD, CAD, CHF, CNY, SEK, NZD

        public $rate; // Taux de conversion entre les AIECoins et la devise spécifiée (exemple 1€ = 25 AIECoins )

        public function __construct($id, $activity, $country, $currencyCode, $rate) {
            $this->id = $id;
            $this->activity = $activity;
            $this->country = $country;
            $this->currencyCode = $currencyCode;
            $this->rate = $rate;
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

        public function getCurrencyCode() {
            return $this->currencyCode;
        }

        public function getRate() {
            return $this->rate;
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

        public function setCurrencyCode($currencyCode) {
            $this->currencyCode = $currencyCode;
        }

        public function setRate($rate) {
            $this->rate = $rate;
        }

        


    }