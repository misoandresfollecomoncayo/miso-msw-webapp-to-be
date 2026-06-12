<?php

/**
 * Description of Warehouse
 *
 * @author root
 */
class Warehouse {
    
    private $idWarehouse;
    private $name;
    
    public function __construct($idWarehouse, $name) {
        $this->idWarehouse = $idWarehouse;
        $this->name = $name;
    }

    public function getIdWarehouse() {
        return $this->idWarehouse;
    }

    public function getName() {
        return $this->name;
    }


    
}
