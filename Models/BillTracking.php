<?php

class BillTracking {
    
    private $idBillTracking;
    
    private $description;
    
    private $idSystemUser;
    
    private $createdTimestamp;
    
    private $type;
    
    private $idBill;
    
    public function __construct($idBillTracking, $description, $idSystemUser, $createdTimestamp, $type, $idBill) {
        $this->idBillTracking = $idBillTracking;
        $this->description = $description;
        $this->idSystemUser = $idSystemUser;
        $this->createdTimestamp = $createdTimestamp;
        $this->type = $type;
        $this->idBill = $idBill;
    }

    public function getIdBillTracking() {
        return $this->idBillTracking;
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

    public function getBill() {
        return BillDAO::getBillById($this->idBill);
    }
    
}
