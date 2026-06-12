<?php

class LogisticOperator {
    
    private $idLogisticOperator;
    
    private $name;

    private $URLQuery;

    private $idCountry;
    
    public function __construct($idLogisticOperator, $name, $URLQuery, $idCountry) {
        $this->idLogisticOperator = $idLogisticOperator;
        $this->name = $name;
        $this->URLQuery = $URLQuery;
        $this->idCountry = $idCountry;
    }
    
    public function getIdLogisticOperator() {
        return $this->idLogisticOperator;
    }

    public function getName() {
        return $this->name;
    }

    public function getURLQuery() {
        return $this->URLQuery;
    }

    public function getCountry() {
        return CountryDAO::getCountryById($this->idCountry);
    }
    
}