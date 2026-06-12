<?php

class InventoryCustomer {
    
    public $id;
    public $name;
    public $documentNumber;
    public $idCity;
    public $address;
    public $phoneNumber;
    public $email;
    
    public function __construct($id, $name, $documentNumber, $idCity, $address, $phoneNumber, $email) {
        $this->id = $id;
        $this->name = $name;
        $this->documentNumber = $documentNumber;
        $this->idCity = $idCity;
        $this->address = $address;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
    }

    public function getCity() {
        return CityDAO::getCityById($this->idCity);
    }

}