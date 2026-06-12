<?php

class ShippingPartialPayment {

    private $idShippingPartialPayment;
    
    private $date;
    
    private $amount;
    
    private $idPaymentMethod;
    
    private $idShipping;
    
    private $idUser;
    
    private $createdTimestamp;
    
    public function __construct($idShippingPartialPayment, $date, $amount, $idPaymentMethod, $idShipping, $idUser, $createdTimestamp) {
        $this->idShippingPartialPayment = $idShippingPartialPayment;
        $this->date = $date;
        $this->amount = $amount;
        $this->idPaymentMethod = $idPaymentMethod;
        $this->idShipping = $idShipping;
        $this->idUser = $idUser;
        $this->createdTimestamp = $createdTimestamp;
    }
    
    public function getIdShippingPartialPayment() {
        return $this->idShippingPartialPayment;
    }

    public function getDate() {
        return $this->date;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getPaymentMethod() {
        return PaymentMethodDAO::getPaymentMethodById($this->idPaymentMethod);
    }

    public function getShipping() {
        return ShippingDAO::getShippingById($this->idShipping);
    }

    public function getUser() {
        return SystemUserDAO::getSystemUserById($this->idUser);
    }

    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }
    
}
