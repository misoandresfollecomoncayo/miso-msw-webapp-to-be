<?php

class ShippingStatus {
    
    private $idShippingStatus;
    
    private $name;

    private $order;
    
    public function __construct($idShippingStatus, $name, $order) {
        $this->idShippingStatus = $idShippingStatus;
        $this->name = $name;
        $this->order = $order;
    }
    
    public function getIdShippingStatus() {
        return $this->idShippingStatus;
    }

    public function getName() {
        return $this->name;
    }

    public function getOrder() {
        return $this->order;
    }
    
}