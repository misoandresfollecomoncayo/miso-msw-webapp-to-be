<?php

/**
 * Description of Pasto
 *
 * @author Andrés Folleco Moncayo
 */
class PurchasesAgentItem {
    
    const STATUS_PENDING = "PENDING";
    const STATUS_PAID = "PAID";
    
    public $idPurchasesAgentItem;
    public $date;
    public $TRM;
    public $detail;
    public $realCostPurchaseUSD;
    public $freightSaleUSD;
    public $totalCostUSD;
    public $totalCostCOP;
    public $salePriceCOP;
    public $utilityCOP;
    public $freightUSD;
    public $status;
    public $reference;
    
    public function __construct($idPurchasesAgentItem, $date, $TRM, $detail, $realCostPurchaseUSD, $freightSaleUSD, $totalCostUSD, $totalCostCOP, $salePriceCOP, $utilityCOP, $freightUSD, $status, $reference) {
        $this->idPurchasesAgentItem = $idPurchasesAgentItem;
        $this->date = $date;
        $this->TRM = $TRM;
        $this->detail = $detail;
        $this->realCostPurchaseUSD = $realCostPurchaseUSD;
        $this->freightSaleUSD = $freightSaleUSD;
        $this->totalCostUSD = $totalCostUSD;
        $this->totalCostCOP = $totalCostCOP;
        $this->salePriceCOP = $salePriceCOP;
        $this->utilityCOP = $utilityCOP;
        $this->freightUSD = $freightUSD;
        $this->status = $status;
        $this->reference = $reference;
    }
    
    public function getIdPurchasesAgentItem() {
        return $this->idPurchasesAgentItem;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTRM() {
        return $this->TRM;
    }
    
    public function getDetail() {
        return $this->detail;
    }

    public function getRealCostPurchaseUSD() {
        return $this->realCostPurchaseUSD;
    }

    public function getFreightSaleUSD() {
        return $this->freightSaleUSD;
    }

    public function getTotalCostUSD() {
        return $this->totalCostUSD;
    }

    public function getTotalCostCOP() {
        return $this->totalCostCOP;
    }

    public function getSalePriceCOP() {
        return $this->salePriceCOP;
    }

    public function getUtilityCOP() {
        return $this->utilityCOP;
    }

    public function getFreightUSD() {
        return $this->freightUSD;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getStatusColor() {
        switch ($this->getStatus()) {
            case PurchasesAgentItem::STATUS_PENDING :
                return "background-color-red";
            default :
                return "background-colo-green";
        }
    }
    
    public function getReference() {
        return $this->reference;
    }

    
}
