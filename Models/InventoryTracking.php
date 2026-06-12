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
class InventoryTracking {
    
    public $id;
    public $detail;
    public $idInventory;
    public $user;
    public $createdTimestamp;
    
    public function __construct($id, $detail, $idInventory, $user, $createdTimestamp) {
        $this->id = $id;
        $this->detail = $detail;
        $this->idInventory = $idInventory;
        $this->user = $user;
        $this->createdTimestamp = $createdTimestamp;
    }

    
}
