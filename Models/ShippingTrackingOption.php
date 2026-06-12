<?php

class ShippingTrackingOption {
    
    private $idShippingTrackingOption;
    
    private $text;
    
    private $order;
    
    public function __construct($idShippingTrackingOption, $text, $order) {
        $this->idShippingTrackingOption = $idShippingTrackingOption;
        $this->text = $text;
        $this->order = $order;
    }

    public function getIdShippingTrackingOption() {
        return $this->idShippingTrackingOption;
    }

    public function getText() {
        return $this->text;
    }
    
    public function getOrder() {
        return $this->order;
    }

}
