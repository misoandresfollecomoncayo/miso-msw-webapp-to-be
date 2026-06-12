<?php

class Ecuador {
    
    private $idEcuador;
    
    private $billNumber;
    
    private $customerNames;
    
    private $createdTimestamp;
    
    public function __construct($idEcuador, $billNumber, $customerNames, $createdTimestamp) {
        $this->idEcuador = $idEcuador;
        $this->billNumber = $billNumber;
        $this->customerNames = $customerNames;
        $this->createdTimestamp = $createdTimestamp;
    }

    public function getIdEcuador() {
        return $this->idEcuador;
    }

    public function getBillNumber() {
        return $this->billNumber;
    }

    public function getCustomerNames() {
        return $this->customerNames;
    }
    
    public function getItems() {
        return EcuadorDAO::getItemsByList($this->idEcuador);
    }

    public function getQuantity() {
        $items = $this->getItems();
        $value = 0;
        
        foreach ($items as $i) {
            $value += $i->getQuantity();
        }
        
        return $value;
    }
    
    public function getCompletedQuantity() {
        $items = $this->getItems();
        $value = 0;
        
        foreach ($items as $i) {
            if ($i->getStatus() == EcuadorItem::STATUS_COMPLETED) {
                $value += $i->getQuantity();
            }
        }
        
        return $value;
    }
    
    public function getStatus() {
        $items = $this->getItems();
        
        foreach ($items as $i) {
            if ($i->getStatus() == EcuadorItem::STATUS_PENDING) {
                return "PENDIENTE";
            }
        }
        
        return "COMPLETADO";
    }
    
    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }

}
