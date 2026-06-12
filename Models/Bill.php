<?php

use Cloud\Engine\PHP\Utils\CloudEngineStrings;

class Bill {

    const CURRENCY_COP = "COP";
    
    const CURRENCY_USD = "USD";
    
    private $idBill;
    
    private $billNumber;
    
    private $idCustomer;
    
    private $from;
    
    private $fromAddress;
    
    private $fromPhone;
    
    private $to;
    
    private $toAddress;
    
    private $toPhone;
    
    private $toCountry;
    
    private $currency;
    
    private $idShipmentCompany;
    
    private $annulled;
    
    private $createdTimestamp;
    
    public function __construct($idBill, $billNumber, $idCustomer, $from, $fromAddress, $fromPhone, $to, $toAddress, $toPhone, $toCountry, $currency, $idShipmentCompany, $annulled, $createdTimestamp) {
        $this->idBill = $idBill;
        $this->billNumber = "UNI" . $billNumber;
        $this->idCustomer = $idCustomer;
        $this->from = $from;
        $this->fromAddress = $fromAddress;
        $this->fromPhone = $fromPhone;
        $this->to = $to;
        $this->toAddress = $toAddress;
        $this->toPhone = $toPhone;
        $this->toCountry = $toCountry;
        $this->currency = $currency;
        $this->idShipmentCompany = $idShipmentCompany;
        $this->annulled = $annulled;
        $this->createdTimestamp = $createdTimestamp;
    }

    public function getIdBill() {
        return $this->idBill;
    }

    public function getBillNumber() {
        return $this->billNumber;
    }

    public function getCustomer() {
        return CustomerDAO::getCustomerById($this->idCustomer);
    }
    
    public function getFrom() {
        return $this->from;
    }
    
    public function getFromAddress() {
        return $this->fromAddress;
    }

    public function getFromPhone() {
        return $this->fromPhone;
    }
    
    public function getTo() {
        return $this->to;
    }
    
    public function getToAddress() {
        return $this->toAddress;
    }

    public function getToPhone() {
        return $this->toPhone;
    }
    
    public function getToCountry() {
        return CountryDAO::getCountryById($this->toCountry);
    }
    
    public function getCurrency() {
        return $this->currency;
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
    
    public function getCreatedTimestampPDFFormat() {
        $dt = new DateTime($this->createdTimestamp);
        return $dt->format("M dS, Y");
    }
    
    public function getItems() {
        return BillItemDAO::getBillItemsByBill($this);
    }
    
    public function getBoxesConcatenated() {
        $value = '';
        $items = $this->getItems();
        
        foreach ($items as $i) {
            $value .= $i->getBoxNumber() . ($i != end($items) ? " - " : "");
        }
        
        return $value;
    }
    
    public function getTotal() {
        $total = 0;
        $items = $this->getItems();
        
        foreach ($items as $i) {
            $total += $i->getAmount();
        }
        
        return $total;
    }
    
    public function getPaymentColor() {
        if ($this->wasAnnulled()) {
            return "background-color-black";
        }
        
        if (count($this->getPartialPayments()) == 0) {
            return "background-color-red";
        }
        
        if (round($this->getPendingPayment(),2) > 0) {
            return "background-color-yellow";
        }
        
        if (round($this->getTotalPartialPayments(),2) > round($this->getTotal(),2)) {
            return "background-color-twitter";
        }
        
        return "background-color-green";
    }
    
    public function getPaymentStatusLanguage($language) {
        if ($this->wasAnnulled()) {
            return $language == Customer::LANGUAGE_SPANISH ? "ANULADA" : "VOID";
        }
        
        if (count($this->getPartialPayments()) == 0) {
            return $language == Customer::LANGUAGE_SPANISH ? "SIN PAGAR" : "PENDING";
        }
        
        if (round($this->getPendingPayment(),2) > 0) {
            return $language == Customer::LANGUAGE_SPANISH ? "PARCIALES" : "PARTIAL";
        }
        
        if (round($this->getTotalPartialPayments(),2) > round($this->getTotal(),2)) {
            return $language == Customer::LANGUAGE_SPANISH ? "VERIFICAR" : "VERIFY";
        }
        
        return $language == Customer::LANGUAGE_SPANISH ? "PAGADA" : "PAID";
    }
    
    public function getPaymentStatus() {
        if ($this->wasAnnulled()) {
            return "ANULADA";
        }
        
        if (count($this->getPartialPayments()) == 0) {
            return "SIN PAGAR";
        }
        
        if (round($this->getPendingPayment(),2) > 0) {
            return "PARCIALES";
        }
        
        if (round($this->getTotalPartialPayments(),2) > round($this->getTotal(),2)) {
            return "VERIFICAR";
        }
        
        return "PAGADA";
    }
    
    public function getPendingPayment() {
        return $this->getTotal() - $this->getTotalPartialPayments();
    }
    
    public function getPartialPayments() {
        return BillPartialPaymentDAO::getPartialPaymentsByBill($this);
    }
    
    public function getTotalPartialPayments() {
        $total = 0;
        $partialPayments = $this->getPartialPayments();
        
        foreach ($partialPayments as $p) {
            $total += $p->getAmount();
        }
        
        return $total;
    }
    
    public function getTracking() {
        return BillTrackingDAO::getTrackingsByBill($this);
    }
    
    public function getWeight() {
        $total = 0;
        $items = $this->getItems();
        foreach ($items as $i) {
            $total += $i->getWeight();
        }
        return $total;
    }
    
    public function hasPicture() {
        return file_exists(PRIVATE_PATH_UPLOADS . "Invoices/" . $this->getIdBill());
    }
    
}
