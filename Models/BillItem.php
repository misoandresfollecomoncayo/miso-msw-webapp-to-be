<?php


class BillItem {
    
    public $idBillItem;
    
    private $description;
    
    public $boxNumber;
    
    private $weight;
    
    private $amount;
    
    private $idBill;
    
    private $delivered;
    
    public function __construct($idBillItem, $description, $boxNumber, $weight, $amount, $idBill, $delivered) {
        $this->idBillItem = $idBillItem;
        $this->description = $description;
        $this->boxNumber = $boxNumber;
        $this->weight = $weight;
        $this->amount = $amount;
        $this->idBill = $idBill;
        $this->delivered = $delivered;
    }
    
    public function getIdBillItem() {
        return $this->idBillItem;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getBoxNumber() {
        return $this->boxNumber;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getIdBill() {
        return $this->idBill;
    }
    
    public function getTracking() {
        return BillItemTrackingDAO::getByBillItem($this);
    }
    
    public function wasDelivered() {
        return $this->delivered;
    }
    
}
