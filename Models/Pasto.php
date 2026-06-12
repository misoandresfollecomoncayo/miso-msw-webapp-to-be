<?php

/**
 * Description of Pasto
 *
 * @author Andrés Folleco Moncayo
 */
class Pasto {
    
    public $idPasto;
    public $date;
    public $detail;
    public $realCostPurchaseUSD;
    public $freightSaleUSD;
    public $totalCostUSD;
    public $totalCostCOP;
    public $salePriceCOP;
    public $utilityCOP;
    public $freightUSD;
    public $responsible;
    public $status;
    
    public function __construct($idPasto, $date, $detail, $realCostPurchaseUSD, $freightSaleUSD, $totalCostUSD, $totalCostCOP, $salePriceCOP, $utilityCOP, $freightUSD, $responsible, $status) {
        $this->idPasto = $idPasto;
        $this->date = $date;
        $this->detail = $detail;
        $this->realCostPurchaseUSD = $realCostPurchaseUSD;
        $this->freightSaleUSD = $freightSaleUSD;
        $this->totalCostUSD = $totalCostUSD;
        $this->totalCostCOP = $totalCostCOP;
        $this->salePriceCOP = $salePriceCOP;
        $this->utilityCOP = $utilityCOP;
        $this->freightUSD = $freightUSD;
        $this->responsible = $responsible;
        $this->status = $status;
    }
    
    public function getIdPasto() {
        return $this->idPasto;
    }

    public function getDate() {
        return $this->date;
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

    public function getResponsible() {
        return $this->responsible;
    }

    public function getStatus() {
        return $this->status;
    }



    
}
