<?php

class PurchaseStatus {
    
    private $idPurchaseStatus;
    
    private $name;

    private $order;
    
    public function __construct($idPurchaseStatus, $name, $order) {
        $this->idPurchaseStatus = $idPurchaseStatus;
        $this->name = $name;
        $this->order = $order;
    }
    
    public function getIdPurchaseStatus() {
        return $this->idPurchaseStatus;
    }

    public function getName() {
        return $this->name;
    }

    public function getOrder() {
        return $this->order;
    }
    
}