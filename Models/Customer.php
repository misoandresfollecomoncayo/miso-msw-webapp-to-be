<?php

use Cloud\Engine\PHP\Utils\CloudEngineStrings;

class Customer {
    
    const LANGUAGE_ENGLISH = "ENGLISH";
    
    const LANGUAGE_SPANISH = "SPANISH";
    
    private $idCustomer;
    
    private $lockerNumber;

    private $names;

    private $gender;

    private $birthdate;

    private $idDocumentType;

    private $documentNumber;

    private $idCity;

    private $address;

    private $telephone;

    private $telephone2;

    private $email;

    private $password;

    private $active;

    private $deleted;

    private $createdTimestamp;
    
    private $idRole;

    private $language;
    
    public function __construct($idCustomer, $lockerNumber, $names, $gender, $birthdate, $idDocumentType, $documentNumber, $idCity, $address, $telephone, $telephone2, $email, $password, $active, $deleted, $createdTimestamp, $idRole, $language) {
        $this->idCustomer = $idCustomer;
        $this->lockerNumber = $lockerNumber;
        $this->names = $names;
        $this->gender = $gender;
        $this->birthdate = $birthdate;
        $this->idDocumentType = $idDocumentType;
        $this->documentNumber = $documentNumber;
        $this->idCity = $idCity;
        $this->address = $address;
        $this->telephone = $telephone;
        $this->telephone2 = $telephone2;
        $this->email = $email;
        $this->password = $password;
        $this->active = $active;
        $this->deleted = $deleted;
        $this->createdTimestamp = $createdTimestamp;
        $this->idRole = $idRole;
        $this->language = $language;
    }
    
    public function getIdCustomer() {
        return $this->idCustomer;
    }

    public function getLockerNumber() {
        return $this->lockerNumber;
    }

    public function getNames() {
        return $this->names;
    }
    
    public function getGender() {
        return $this->gender;
    }
    
    public function getGenderSpanish(){
        return $this->getGender() == "MALE" ? "Hombre" : "Mujer";
    }

    public function getBirthdate() {
        return $this->birthdate;
    }

    public function getBirthdateFormatted() {
        return CloudEngineStrings::timestampToShortHumanFormat($this->getBirthdate());
    }
    
    public function getDocumentType() {
        return DocumentTypeDAO::getDocumentTypeById($this->idDocumentType);
    }
    
    public function getDocumentTypeName() {
        return ($this->getDocumentType() != null) ? $this->getDocumentType()->getName() : "";
    }

    public function getDocumentNumber() {
        return $this->documentNumber;
    }

    public function getCity() {
        return CityDAO::getCityById($this->idCity);
    }

    public function getAddress() {
        return $this->address;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getTelephone2() {
        return $this->telephone2;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function isActive() {
        return $this->active;
    }
    
    public function getActiveString() {
        return $this->active ? "ACTIVO" : "INACTIVO";
    }
    
    public function getActiveColor() {
        return $this->active ? "background-color-green" : "background-color-red";
    }

    public function isDeleted() {
        return $this->deleted;
    }

    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }
    
    public function getUnreadNotifications() {
        return NotificationDAO::getUnreadNotificationsByIdUser($this->idCustomer);
    }
    
    public function getNotifications() {
        return NotificationDAO::getNotificationsByIdUser($this->idCustomer);
    }
    
    public function getRole() {
        return RoleDAO::getRoleById($this->idRole);
    }
    
    public function getPurchases() {
        return PurchaseDAO::getPurchasesByCustomer($this);
    }
    
    public function warehouseWeight() {
        $value = 0;
        
        $purchases = $this->getPendingPurchases();
        
        foreach ($purchases as $p) {
            $value += $p->getNetWeight();
        }
        
        return number_format($value, 2);
    }
    
    public function getLanguage() {
        return $this->language;
    }
    
    public function getPendingPurchases() {
        return PurchaseDAO::getPendingPurchasesByCustomer($this);
    }
    
    public function getShipments() {
        return ShippingDAO::getShippingsByCustomer($this);
    }

    public function getBills() {
        return BillDAO::getBillsByIdCustomer($this->getIdCustomer());
    }
    
    public function getArrivalAlerts() {
        return ArrivalAlertDAO::getArrivalAlertsByIdCustomer($this->idCustomer);
    }
    
    public function getPaid() {
        $value = 0;
        
        $invoices = $this->getBills();
        $shipments = $this->getShipments();
        
        foreach ($invoices as $i) {
            if (!$i->wasAnnulled()) {
                $value += $i->getTotalPartialPayments();
            }
        }
        
        foreach ($shipments as $s) {
            if (!$s->wasAnnulled()) {
                $value += $s->getTotalPartialPayments();
            }
        }
        
        return number_format($value,2);
    }
    
    public function getPendingPayment() {
        $value = 0;
        
        $invoices = $this->getBills();
        $shipments = $this->getShipments();
        
        foreach ($invoices as $i) {
            if (!$i->wasAnnulled()) {
                $value += $i->getPendingPayment();
            }
        }
        
        foreach ($shipments as $s) {
            if (!$s->wasAnnulled()) {
                $value += $s->getPendingPayment();
            }
        }
        
        return number_format($value,2);
    }
    
}