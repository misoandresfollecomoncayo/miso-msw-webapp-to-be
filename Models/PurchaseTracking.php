<?php

class PurchaseTracking {
    
    const TYPE_PUBLIC = "PUBLIC";
    
    const TYPE_PRIVATE = "PRIVATE";
    
    private $idPurchaseTracking;
    
    private $description;
    
    private $idSystemUser;
    
    private $createdTimestamp;
    
    private $type;
    
    private $idPurchase;
    
    public function __construct($idPurchaseTracking, $description, $idSystemUser, $createdTimestamp, $type, $idPurchase) {
        $this->idPurchaseTracking = $idPurchaseTracking;
        $this->description = $description;
        $this->idSystemUser = $idSystemUser;
        $this->createdTimestamp = $createdTimestamp;
        $this->type = $type;
        $this->idPurchase = $idPurchase;
    }

    public function getIdPurchaseTracking() {
        return $this->idPurchaseTracking;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getSystemUser() {
        return SystemUserDAO::getSystemUserById($this->idSystemUser);
    }

    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }
    
    public function getType() {
        return $this->type;
    }

    public function getPurchase() {
        return PurchaseDAO::getPurchaseById($this->idPurchase);
    }
    
}
