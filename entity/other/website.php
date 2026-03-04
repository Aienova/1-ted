<?php

    class website {
        private $id;
        private $url;
        private $company_id;

        public function __construct($id, $url, $company_id) {
            $this->id = $id;
            $this->url = $url;
            $this->company_id = $company_id;
        }

        public function getId() {
            return $this->id;
        }

        public function getUrl() {
            return $this->url;
        }

        public function getCompanyId() {
            return $this->company_id;
        }
    }