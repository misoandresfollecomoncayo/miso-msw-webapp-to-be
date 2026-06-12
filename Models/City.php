<?php

class City {
    
    private $idCity;
    
    private $name;

    private $idCountry;
    
    public function __construct($idCity, $name, $idCountry) {
        $this->idCity = $idCity;
        $this->name = $name;
        $this->idCountry = $idCountry;
    }
    
    public function getIdCity() {
        return $this->idCity;
    }

    public function getName() {
        return $this->name;
    }

    public function getCountry() {
        return CountryDAO::getCountryById($this->idCountry);
    }
    
}