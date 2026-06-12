<?php

class Permission {
    
    const NOTIFICATIONS = "Notifications.php";
    
    private $idPermission;
    
    private $name;
    
    private $nameEnglish;
    
    private $file;
    
    private $icon;
    
    private $order;
    
    public function __construct($idPermission, $name, $nameEnglish, $file, $icon, $order) {
        $this->idPermission = $idPermission;
        $this->name = $name;
        $this->nameEnglish = $nameEnglish;
        $this->file = $file;
        $this->icon = $icon;
        $this->order = $order;
    }
    
    public function getIdPermission() {
        return $this->idPermission;
    }

    public function getName() {
        return $this->name;
    }
    
    public function getNameEnglish() {
        return $this->nameEnglish;
    }
    
    public function getInternationalName($lang = "SPANISH") {
        switch ($lang) {
            case Customer::LANGUAGE_SPANISH:
                return $this->getName();
            default:
                return $this->getNameEnglish();
        }
    }
    
    public function getFile() {
        return $this->file;
    }

    public function getIcon() {
        return $this->icon;
    }
    
    public function getOrder() {
        return $this->order;
    }

}