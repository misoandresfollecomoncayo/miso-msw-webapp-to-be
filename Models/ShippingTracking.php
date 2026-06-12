<?php

class ShippingTracking {
    
    private $idShippingTracking;
    
    private $description;
    
    private $idSystemUser;
    
    private $createdTimestamp;
    
    private $type;
    
    private $idShipping;
    
    public function __construct($idShippingTracking, $description, $idSystemUser, $createdTimestamp, $type, $idShipping) {
        $this->idShippingTracking = $idShippingTracking;
        $this->description = $description;
        $this->idSystemUser = $idSystemUser;
        $this->createdTimestamp = $createdTimestamp;
        $this->type = $type;
        $this->idShipping = $idShipping;
    }

    public function getIdShippingTracking() {
        return $this->idShippingTracking;
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

    public function getShipping() {
        return ShippingDAO::getShippingById($this->idShipping);
    }
    
}
