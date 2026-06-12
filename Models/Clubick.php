<?php

class Clubick {
    
    public $id;
    public $number;
    public $date;
    public $customer;
    public $customerDocument;
    public $customerAddress;
    public $customerPhone;
    public $product;
    public $trm;
    public $usdPrice;
    public $copPrice;
    public $UniexpressShippingPrice;
    public $totalPrice;
    public $salePrice;
    public $nationalShippingPrice;
    public $totalToPay;
    public $status;
    public $utility;
    public $utilitySantiago;
    public $utilityJulian;
    
    public $invoiceNumber;
    public $paid;
    public $pendingPayment;

    public function __construct($id, $number, $date, $customer, $customerDocument, $customerAddress, $customerPhone, $product, $trm, $usdPrice, $copPrice, $UniexpressShippingPrice, $totalPrice, $salePrice, $nationalShippingPrice, $totalToPay, $status, $utility, $utilitySantiago, $utilityJulian) {
        $this->id = $id;
        $this->number = $number;
        $this->date = $date;
        $this->customer = $customer;
        $this->customerDocument = $customerDocument;
        $this->customerAddress = $customerAddress;
        $this->customerPhone = $customerPhone;
        $this->product = $product;
        $this->trm = $trm;
        $this->usdPrice = $usdPrice;
        $this->copPrice = $copPrice;
        $this->UniexpressShippingPrice = $UniexpressShippingPrice;
        $this->totalPrice = $totalPrice;
        $this->salePrice = $salePrice;
        $this->nationalShippingPrice = $nationalShippingPrice;
        $this->totalToPay = $totalToPay;
        $this->status = $status;
        $this->utility = $utility;
        $this->utilitySantiago = $utilitySantiago;
        $this->utilityJulian = $utilityJulian;
        
        $this->invoiceNumber = "CB" . $this->number;
        $this->paid = $this->getPaid();
        $this->pendingPayment = $this->getPendingPayment();
    }

    public function getId() {
        return $this->id;
    }

    public function getDate() {
        return $this->date;
    }

    public function getCustomer() {
        return $this->customer;
    }
    
    public function getCustomerDocument() {
        return $this->customerDocument;
    }
    
    public function getCustomerAddress() {
        return $this->customerAddress;
    }

    public function getCustomerPhone() {
        return $this->customerPhone;
    }

    public function getProduct() {
        return $this->product;
    }

    public function getInvoice() {
        return $this->invoice;
    }

    public function getTrm() {
        return $this->trm;
    }

    public function getUsdPrice() {
        return $this->usdPrice;
    }

    public function getCopPrice() {
        return $this->copPrice;
    }

    public function getUniexpressShippingPrice() {
        return $this->UniexpressShippingPrice;
    }

    public function getTotalPrice() {
        return $this->totalPrice;
    }

    public function getSalePrice() {
        return $this->salePrice;
    }

    public function getNationalShippingPrice() {
        return $this->nationalShippingPrice;
    }

    public function getTotalToPay() {
        return $this->totalToPay;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getUtility() {
        return $this->utility;
    }

    public function getUtilitySantiago() {
        return $this->utilitySantiago;
    }

    public function getUtilityJulian() {
        return $this->utilityJulian;
    }
    
    public function getPaid() {
        $paid = 0;
        
        $payments = ClubickPaymentDAO::getAllByIdClubick($this->id);
        for ($i=0; $i<count($payments); $i++) {
            $paid += $payments[$i]->amount;
        }
        
        return $paid;
    }
    
    public function getPendingPayment() {
        return $this->totalToPay - $this->getPaid();
    }
    
}