<?php

class ArrivalAlert {
    
    const STATUS_PENDING = "PENDING";
    
    const STATUS_RECEIVED = "RECEIVED";
    
    const STATUS_CANCELED = "CANCELED";
    
    private $idArrivalAlert;
    
    private $trackingNumber;
    
    private $purchase;
    
    private $items;
    
    private $idStore;
    
    private $status;
    
    private $idCustomer;
    
    private $createdTimestamp;
    
    public function __construct($idArrivalAlert, $trackingNumber, $purchase, $items, $idStore, $status, $idCustomer, $createdTimestamp) {
        $this->idArrivalAlert = $idArrivalAlert;
        $this->trackingNumber = $trackingNumber;
        $this->purchase = $purchase;
        $this->items = $items;
        $this->idStore = $idStore;
        $this->status = $status;
        $this->idCustomer = $idCustomer;
        $this->createdTimestamp = $createdTimestamp;
    }

    public function getIdArrivalAlert() {
        return $this->idArrivalAlert;
    }

    public function getTrackingNumber() {
        return $this->trackingNumber;
    }

    public function getPurchase() {
        return $this->purchase;
    }

    public function getItems() {
        return $this->items;
    }

    public function getStore() {
        return StoreDAO::getStoreById($this->idStore);
    }

    public function getStatusLanguage($language) {
        switch ($this->status) {
            case ArrivalAlert::STATUS_PENDING:
                return $language == Customer::LANGUAGE_SPANISH ? "PENDIENTE" : "PENDING";
            case ArrivalAlert::STATUS_RECEIVED:
                return $language == Customer::LANGUAGE_SPANISH ? "RECIBIDO" : "RECEIVED";
            case ArrivalAlert::STATUS_CANCELED:
                return $language == Customer::LANGUAGE_SPANISH ? "CANCELADO" : "CANCELED";
        }
    }
    
    public function getStatus() {
        return $this->status;
    }

    public function getStatusColor() {
        switch ($this->status) {
            case ArrivalAlert::STATUS_PENDING:
                return "background-color-orange";
            case ArrivalAlert::STATUS_RECEIVED:
                return "background-color-green";
            case ArrivalAlert::STATUS_CANCELED:
                return "background-color-red";
        }
    }
    
    public function getCustomer() {
        return CustomerDAO::getCustomerById($this->idCustomer);
    }

    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }
    
}
