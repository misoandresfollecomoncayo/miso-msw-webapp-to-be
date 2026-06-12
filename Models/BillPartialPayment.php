<?php

class BillPartialPayment {
    
    private $idBillPartialPayment;
    
    private $date;
    
    private $amount;
    
    private $idPaymentMethod;
    
    private $idBill;
    
    private $idUser;
    
    private $createdTimestamp;
    
    public function __construct($idBillPartialPayment, $date, $amount, $idPaymentMethod, $idBill, $idUser, $createdTimestamp) {
        $this->idBillPartialPayment = $idBillPartialPayment;
        $this->date = $date;
        $this->amount = $amount;
        $this->idPaymentMethod = $idPaymentMethod;
        $this->idBill = $idBill;
        $this->idUser = $idUser;
        $this->createdTimestamp = $createdTimestamp;
    }
    
    public function getIdBillPartialPayment() {
        return $this->idBillPartialPayment;
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

    public function getBill() {
        return BillDAO::getBillById($this->idBill);
    }

    public function getUser() {
        return SystemUserDAO::getSystemUserById($this->idUser);
    }

    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }

}
