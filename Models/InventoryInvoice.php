<?php

class InventoryInvoice {
    
    public $id;
    public $invoiceNumber;
    public $sellingCompany;
    public $idInventoryCustomer;
    public $createdTimestamp;
    public $annulled;
    public $fullInvoiceCode;
    
    public function __construct($id, $invoiceNumber, $sellingCompany, $idInventoryCustomer, $createdTimestamp, $annulled) {
        $this->id = $id;
        $this->invoiceNumber = $invoiceNumber;
        $this->sellingCompany = $sellingCompany;
        $this->idInventoryCustomer = $idInventoryCustomer;
        $this->createdTimestamp = $createdTimestamp;
        $this->annulled = $annulled;
        
        $this->fullInvoiceCode = ($sellingCompany == "Uniexpress" ? "UN" : "CB") . $this->invoiceNumber;
    }

    public function getItems() {
        return InventoryDAO::getByIdInvoice($this->id);
    }
    
    public function getCustomer() {
        return InventoryCustomerDAO::getById($this->idInventoryCustomer);
    }
    
    public function getTotal() {
        $result = 0;
        
        $items = $this->getItems();
        foreach($items as $i) {
            $result += $i->salePrice;
        }
        
        return $result;
    }
    
    public function getPaid() {
        $result = 0;
        
        $items = $this->getPayments();
        foreach($items as $i) {
            $result += $i->amount;
        }
        
        return $result;
    }
    
    public function getPendingPayment() {
        return  $this->getTotal() - $this->getPaid();
    }
    
    public function getTracking() {
        return InventoryInvoiceTrackingDAO::getByIdInventoryInvoice($this->id);
    }
    
    public function getPayments() {
        return InventoryInvoicePaymentDAO::getByIdInventoryInvoice($this->id);
    }
    
}