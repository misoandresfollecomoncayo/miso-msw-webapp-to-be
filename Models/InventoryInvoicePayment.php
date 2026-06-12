<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InventoryTracking
 *
 * @author andres
 */
class InventoryInvoicePayment {
    
    public $id;
    public $amount;
    public $idPaymentMethod;
    public $idInventoryInvoice;
    public $user;
    public $createdTimestamp;
    public $date;
    
    public function __construct($id, $amount, $idPaymentMethod, $idInventoryInvoice, $user, $createdTimestamp, $date) {
        $this->id = $id;
        $this->amount = $amount;
        $this->idPaymentMethod = $idPaymentMethod;
        $this->idInventoryInvoice = $idInventoryInvoice;
        $this->user = $user;
        $this->createdTimestamp = $createdTimestamp;
        $this->date = $date;
    }

    public function getPaymentMethod() {
        return InventoryPaymentMethodDAO::getById($this->idPaymentMethod);
    }
    
}
