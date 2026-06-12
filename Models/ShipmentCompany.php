<?php

class ShipmentCompany {
    
    private $idShipmentCompany;
    
    private $name;
    
    public function __construct($idShipmentCompany, $name) {
        $this->idShipmentCompany = $idShipmentCompany;
        $this->name = $name;
    }

    public function getIdShipmentCompany() {
        return $this->idShipmentCompany;
    }

    public function getName() {
        return $this->name;
    }

}