<?php

class Inventory {
    
    public $id;
    public $invoice;
    public $product;
    public $trm;
    public $usdPrice;
    public $copPrice;
    public $internationalShippingPrice;
    public $nationalShippingPrice;
    public $totalCost;
    public $salePrice;
    public $utility;
    public $idInvoice;
    public $createdTimestamp;
    
    public $fullInvoiceCode;
    public $lastTracking;
    public $completeTracking;
    
    public function __construct($id, $invoice, $product, $trm, $usdPrice, $copPrice, $internationalShippingPrice, $nationalShippingPrice, $totalCost, $salePrice, $utility, $idInvoice, $createdTimestamp) {
        $this->id = $id;
        $this->invoice = $invoice;
        $this->product = $product;
        $this->trm = $trm;
        $this->usdPrice = $usdPrice;
        $this->copPrice = $copPrice;
        $this->internationalShippingPrice = $internationalShippingPrice;
        $this->nationalShippingPrice = $nationalShippingPrice;
        $this->totalCost = $totalCost;
        $this->salePrice = $salePrice;
        $this->utility = $utility;
        $this->idInvoice = $idInvoice;
        $this->createdTimestamp = $createdTimestamp;
        
        $this->fullInvoiceCode = "INV" . $this->invoice;
        
        $tracking = $this->getTracking();
        if (count($tracking) > 0) {
            $this->lastTracking = $tracking[0]->detail;

            foreach ($tracking as $t) {
                $this->completeTracking .= $t->detail . " ";
            }
        } else {
            $this->lastTracking = "";
            $this->completeTracking = "";
        }
    }
    
    public function getInvoice() {
        
    }
    
    public function getTracking() {
        return InventoryTrackingDAO::getByIdInventory($this->id);
    }

}