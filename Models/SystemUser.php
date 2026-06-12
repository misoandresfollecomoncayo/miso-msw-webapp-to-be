<?php

class SystemUser {
    
    private $idSystemUser;
    
    private $names;

    private $email;

    private $password;
    
    private $idRole;

    private $requestShipmentNotification;
    
    private $alertArrivalNotification;
    
    private $active;
    
    private $deleted;
    
    private $createdTimestamp;

    public function __construct($idSystemUser, $names, $email, $password, $idRole, $requestShipmentNotification, $alertArrivalNotification, $active, $deleted, $createdTimestamp) {
        $this->idSystemUser = $idSystemUser;
        $this->names = $names;
        $this->email = $email;
        $this->password = $password;
        $this->idRole = $idRole;
        $this->requestShipmentNotification = $requestShipmentNotification;
        $this->alertArrivalNotification = $alertArrivalNotification;
        $this->active = $active;
        $this->deleted = $deleted;
        $this->createdTimestamp = $createdTimestamp;
    }
    
    public function getIdSystemUser() {
        return $this->idSystemUser;
    }

    public function getNames() {
        return $this->names;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRole() {
        return RoleDAO::getRoleById($this->idRole);
    }

    public function sendRequestShipmentNotification() {
        return $this->requestShipmentNotification;
    }
    
    public function sendAlertArrivalNotification() {
        return $this->alertArrivalNotification;
    }
    
    public function isActive() {
        return $this->active;
    }

    public function isDeleted() {
        return $this->deleted;
    }

    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }
    
    public function getUnreadNotifications() {
        return NotificationDAO::getUnreadNotificationsByIdUser($this->idSystemUser);
    }
    
    public function getNotifications() {
        return NotificationDAO::getNotificationsByIdUser($this->idSystemUser);
    }
    
}