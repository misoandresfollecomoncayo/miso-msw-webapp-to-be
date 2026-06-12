<?php

class Country {
    
    private $idCountry;
    
    private $name;

    public function __construct($idCountry, $name) {
        $this->idCountry = $idCountry;
        $this->name = $name;
    }
    
    public function getIdCountry() {
        return $this->idCountry;
    }

    public function getName() {
        return $this->name;
    }

    public function getCities() {
        return CityDAO::getCitiesByCountry($this);
    }
    
    public function getLogisticOperators() {
        return LogisticOperatorDAO::getLogisticOperatorsByCountry($this);
    }
    
}