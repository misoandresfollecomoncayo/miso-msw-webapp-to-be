<?php

/**
 * Description of BillItemTracking
 *
 * @author root
 */
class BillItemTracking {
    
    private $idBillItemTracking;
    
    private $description;
    
    private $idSystemUser;
    
    private $createdTimestamp;
    
    private $idBillItem;
    
    public function __construct($idBillItemTracking, $description, $idSystemUser, $createdTimestamp, $idBillItem) {
        $this->idBillItemTracking = $idBillItemTracking;
        $this->description = $description;
        $this->idSystemUser = $idSystemUser;
        $this->createdTimestamp = $createdTimestamp;
        $this->idBillItem = $idBillItem;
    }

    public function getIdBillItemTracking() {
        return $this->idBillItemTracking;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getIdSystemUser() {
        return $this->idSystemUser;
    }

    public function getSystemUser() {
        return SystemUserDAO::getSystemUserById($this->idSystemUser);
    }
    
    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }

    public function getIdBillItem() {
        return $this->idBillItem;
    }


    
}
