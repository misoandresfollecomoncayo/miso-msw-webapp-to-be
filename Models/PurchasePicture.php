<?php

class PurchasePicture {
    
    private $idPurchasePicture;
    
    private $idPurchase;
    
    public function __construct($idPurchasePicture, $idPurchase) {
        $this->idPurchasePicture = $idPurchasePicture;
        $this->idPurchase = $idPurchase;
    }
    
    public function getIdPurchasePicture() {
        return $this->idPurchasePicture;
    }

    public function getIdPurchase() {
        return $this->idPurchase;
    }
    
}