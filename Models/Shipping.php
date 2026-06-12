<?php

use Cloud\Engine\PHP\Utils\CloudEngineStrings;

class Shipping {
    
    const CURRENCY_COP = "COP";
    
    const CURRENCY_USD = "USD";
    
    private $idShipping;
    
    private $shippingNumber;

    private $status;

    private $netWeight;
    
    private $volumetricWeight;
    
    private $poundValue;
    
    private $volumetricPoundValue;
    
    private $declaredValue;
    
    private $tax;
    
    private $freight;
    
    private $secure;
    
    private $additionalValue;
    
    private $additionalValueDescription;
    
    private $total;
    
    private $currency;
    
    private $sequenceNumber;
    
    private $idShipmentCompany;
    
    private $annulled;
    
    private $createdTimestamp;
    
    private $delivered;
    
    private $TRM;
    
    public function __construct($idShipping, $shippingNumber, $status, $netWeight, $volumetricWeight, $poundValue, $volumetricPoundValue, $declaredValue, $tax, $freight, $secure, $additionalValue, $additionalValueDescription, $total, $currency, $sequenceNumber, $idShipmentCompany, $annulled, $createdTimestamp, $delivered, $TRM) {
        $this->idShipping = $idShipping;
        $this->shippingNumber = "UNI" . $shippingNumber;
        $this->status = $status;
        $this->netWeight = $netWeight;
        $this->volumetricWeight = $volumetricWeight;
        $this->poundValue = $poundValue;
        $this->volumetricPoundValue = $volumetricPoundValue;
        $this->declaredValue = $declaredValue;
        $this->tax = $tax;
        $this->freight = $freight;
        $this->secure = $secure;
        $this->additionalValue = $additionalValue;
        $this->additionalValueDescription = $additionalValueDescription;
        $this->total = $total;
        $this->currency = $currency;
        $this->sequenceNumber = $sequenceNumber;
        $this->idShipmentCompany = $idShipmentCompany;
        $this->annulled = $annulled;
        $this->createdTimestamp = $createdTimestamp;
        $this->delivered = $delivered;
        $this->TRM = $TRM;
    }

    public function getIdShipping() {
        return $this->idShipping;
    }

    public function getShippingNumber() {
        return $this->shippingNumber;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getNetWeight() {
        return $this->netWeight;
    }

    public function getVolumetricWeight() {
        return $this->volumetricWeight;
    }

    public function getPoundValue() {
        return $this->poundValue;
    }

    public function getVolumetricPoundValue() {
        return $this->volumetricPoundValue;
    }

    public function getDeclaredValue() {
        return $this->declaredValue;
    }

    public function getTax() {
        return $this->tax;
    }

    public function getFreight() {
        return $this->freight;
    }

    public function getSecure() {
        return $this->secure;
    }

    public function getAdditionalValue() {
        return $this->additionalValue;
    }

    public function getAdditionalValueDescription() {
        return htmlentities($this->additionalValueDescription);
    }

    public function getTotal() {
        return round($this->tax + $this->freight + $this->secure + $this->additionalValue, 2);
    }

    public function getCurrency() {
        return $this->currency;
    }
    
    public function getSequenceNumber() {
        return $this->sequenceNumber;
    }

    public function getShipmentCompany() {
        return ShipmentCompanyDAO::getShipmentCompanyById($this->idShipmentCompany);
    }
    
    public function wasAnnulled() {
        return $this->annulled;
    }
    
    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }
    
    public function getCreatedTimestampHuman() {
        return CloudEngineStrings::timestampToShortHumanFormat($this->createdTimestamp);
    }
    
    public function getCreatedTimestampPDFFormat($language = "ENGLISH") {
        if ($language == Customer::LANGUAGE_SPANISH) {
            setlocale(LC_ALL, 'es_ES.UTF-8'); 
        }
        return strftime("%b %d, %Y", strtotime($this->createdTimestamp));
    }

    public function getPurchases() {
        return PurchaseDAO::getPurchasesByShipping($this);
    }
    
    public function getTracking() {
        return ShippingTrackingDAO::getTrackingsByShipping($this);
    }
    
    public function getPartialPayments() {
        return ShippingPartialPaymentDAO::getPartialPaymentsByShipping($this);
    }
    
    public function getTotalPartialPayments() {
        $total = 0;
        $partialPayments = $this->getPartialPayments();
        
        foreach ($partialPayments as $p) {
            $total += $p->getAmount();
        }
        
        return $total;
    }
    
    public function getPendingPayment() {
        return $this->getTotal() - $this->getTotalPartialPayments();
    }
    
    public function getPaymentColor() {
        if ($this->wasAnnulled()) {
            return "background-color-black";
        }
        
        if (count($this->getPartialPayments()) == 0) {
            return "background-color-red";
        }
        
        if (round($this->getTotalPartialPayments(),2) < round($this->getTotal(),2)) {
            return "background-color-yellow";
        }
        
        if (round($this->getTotalPartialPayments(),2) == round($this->getTotal(),2)) {
            return "background-color-green";
        }
        
        return "background-color-twitter";
    }
    
    public function getPaymentStatusLanguage($language) {
        if ($this->wasAnnulled()) {
            return $language == Customer::LANGUAGE_SPANISH ? "ANULADA" : "VOID";
        }
        
        if (count($this->getPartialPayments()) == 0) {
            return $language == Customer::LANGUAGE_SPANISH ? "SIN PAGAR" : "PENDING";
        }
        
        if (round($this->getTotalPartialPayments(),2) < round($this->getTotal(),2)) {
            return $language == Customer::LANGUAGE_SPANISH ? "PARCIALES" : "PARTIAL";
        }
        
        if (round($this->getTotalPartialPayments(),2) == round($this->getTotal(),2)) {
            return $language == Customer::LANGUAGE_SPANISH ? "PAGADA" : "PAID";
        }
        
        return $language == Customer::LANGUAGE_SPANISH ? "VERIFICAR" : "VERIFY";
    }
    
    public function getPaymentStatus() {
        if ($this->wasAnnulled()) {
            return "ANULADA";
        }
        
        if (count($this->getPartialPayments()) == 0) {
            return "SIN PAGAR";
        }
        
        if (round($this->getTotalPartialPayments(),2) < round($this->getTotal(),2)) {
            return "PARCIALES";
        }
        
        if (round($this->getTotalPartialPayments(),2) == round($this->getTotal(),2)) {
            return "PAGADA";
        }
        
        return "VERIFICAR";
    }
    
    public function wasDelivered() {
        return $this->delivered;
    }
    
    public function hasPicture() {
        return file_exists(PRIVATE_PATH_UPLOADS . "Invoices/" . $this->getIdShipping());
    }
    
    public function getTRM() {
        return $this->TRM;
    }
    
}