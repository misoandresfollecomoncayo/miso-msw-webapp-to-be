<?php

class PaymentMethod {
    
    private $idPaymentMethod;
    
    private $name;
    
    public function __construct($idPaymentMethod, $name) {
        $this->idPaymentMethod = $idPaymentMethod;
        $this->name = $name;
    }
    
    public function getIdPaymentMethod() {
        return $this->idPaymentMethod;
    }

    public function getName() {
        return $this->name;
    }

}
