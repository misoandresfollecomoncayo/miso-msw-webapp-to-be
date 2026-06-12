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
class InventoryInvoiceTracking {
    
    public $id;
    public $detail;
    public $idInventoryInvoice;
    public $user;
    public $createdTimestamp;
    
    public function __construct($id, $detail, $idInventoryInvoice, $user, $createdTimestamp) {
        $this->id = $id;
        $this->detail = $detail;
        $this->idInventory = $idInventoryInvoice;
        $this->user = $user;
        $this->createdTimestamp = $createdTimestamp;
    }

    
}
