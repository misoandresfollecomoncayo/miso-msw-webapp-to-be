<?php

/**
 * Description of EcuadorItem
 *
 * @author root
 */
class EcuadorItem {
    
    const STATUS_PENDING = "PENDING";
    const STATUS_COMPLETED = "COMPLETED";
    
    private $id;
    private $autoincrement;
    private $quantity;
    private $sequence;
    private $description;
    private $status;
    
    public function __construct($id, $autoincrement, $quantity, $sequence, $description, $status) {
        $this->id = $id;
        $this->autoincrement = $autoincrement;
        $this->quantity = $quantity;
        $this->sequence = $sequence;
        $this->description = $description;
        $this->status = $status;
    }

    public function getId() {
        return $this->id;
    }

    public function getAutoincrement() {
        return $this->autoincrement;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getSequence() {
        return $this->sequence;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getStatus() {
        return $this->status;
    }
    
}
