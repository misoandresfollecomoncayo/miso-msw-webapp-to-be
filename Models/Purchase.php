<?php

use Cloud\Engine\PHP\Utils\CloudEngineStrings;

class Purchase {
    
    const STATUS_WAREHOUSE = "BODEGA";
    
    const STATUS_REQUESTED = "SOLICITADO";
    
    const STATUS_SHIPPED = "ENVIADO";
    
    private $idPurchase;
    
    private $content;

    private $netWeight;

    private $longValue;

    private $width;

    private $high;

    private $idCustomer;

    private $trackingNumber;
    
    private $idStore;

    private $quantity;

    private $idShipping;
    
    private $createdTimestamp;
    
    private $requested;
    
    public function __construct($idPurchase, $content, $netWeight, $longValue, $width, $high, $idCustomer, $trackingNumber, $idStore, $quantity, $idShipping, $createdTimestamp, $requested) {
        $this->idPurchase = $idPurchase;
        $this->content = $content;
        $this->netWeight = $netWeight;
        $this->longValue = $longValue;
        $this->width = $width;
        $this->high = $high;
        $this->idCustomer = $idCustomer;
        $this->trackingNumber = $trackingNumber;
        $this->idStore = $idStore;
        $this->quantity = $quantity;
        $this->idShipping = $idShipping;
        $this->createdTimestamp = $createdTimestamp;
        $this->requested = $requested;
    }
    
    public function getIdPurchase() {
        return $this->idPurchase;
    }

    public function getContent() {
        return htmlentities($this->content);
    }
    
    public function getNetWeight() {
        return $this->netWeight;
    }

    public function getLong() {
        return $this->longValue;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getHigh() {
        return $this->high;
    }

    public function getVolumetricPounds() {
        return round(($this->longValue * $this->width * $this->high) / 166);
    }

    public function getVolumetricWeight() {
        if ($this->getVolumetricPounds() > $this->getNetWeight()) {
            return $this->getVolumetricPounds() - $this->getNetWeight();
        } else {
            return 0;
        }
    }
    
    public function getCustomer() {
        return CustomerDAO::getCustomerById($this->idCustomer);
    }

    public function getTrackingNumber() {
        return $this->trackingNumber;
    }
    
    public function getStore() {
        return StoreDAO::getStoreById($this->idStore);
    }

    public function getStatusLanguage($language) {
        if ($this->getShipment() == null && !$this->wasRequested()) {
            return $language == Customer::LANGUAGE_SPANISH ? "BODEGA" : "WAREHOUSE" ;
        }
        if ($this->getShipment() == null && $this->wasRequested()) {
            return $language == Customer::LANGUAGE_SPANISH ? "SOLICITADO" : "REQUESTED" ;
        }
        if ($this->getShipment() != null) {
            return $language == Customer::LANGUAGE_SPANISH ? "ENVIADO" : "SHIPPED" ;
        }
    }
    
    public function getStatus() {
        if ($this->getShipment() == null && !$this->wasRequested()) {
            return Purchase::STATUS_WAREHOUSE;
        }
        if ($this->getShipment() == null && $this->wasRequested()) {
            return Purchase::STATUS_REQUESTED;
        }
        if ($this->getShipment() != null) {
            return Purchase::STATUS_SHIPPED;
        }
    }
    
    public function getStatusColor() {
        switch ($this->getStatus()) {
            case Purchase::STATUS_WAREHOUSE :
                return "background-color-orange";
            case Purchase::STATUS_SHIPPED :
                return "background-color-green";
            case Purchase::STATUS_REQUESTED:
                return "background-color-yellow";
        }
    }
    
    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }
    
    public function getCreatedTimestampFormatted() {
        return CloudEngineStrings::timestampToHumanFormat($this->getCreatedTimestamp());
    }

    public function getTracking() {
        return PurchaseTrackingDAO::getTrackingsByPurchase($this);
    }
    
    public function getPictures() {
        return PurchasePictureDAO::getPurchasePicturesByPurchase($this);
    }
    
    public function getShipment() {
        return ShippingDAO::getShippingById($this->idShipping);
    }
    
    public function getQuantity() {
        return $this->quantity;
    }
    
    public function wasRequested() {
        return $this->requested;
    }
    
}