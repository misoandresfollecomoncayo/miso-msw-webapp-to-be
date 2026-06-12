<?php

class Token {
    
    const TYPE_SYSTEM_USER = "SystemUser";
    
    const TYPE_CUSTOMER = "Customer";
    
    private $idToken;
    
    private $idUser;
    
    private $type;
    
    private $used;
    
    public function __construct($idToken, $idUser, $type, $used) {
        $this->idToken = $idToken;
        $this->idUser = $idUser;
        $this->type = $type;
        $this->used = $used;
    }
    
    public function getIdToken() {
        return $this->idToken;
    }

    public function getType() {
        return $this->type;
    }

    public function isUsed() {
        return $this->used;
    }

    public function getObject() {
        if ($this->getType() == Access::TYPE_CUSTOMER) {
            return CustomerDAO::getCustomerById($this->idUser);
        } else {
            return SystemUserDAO::getSystemUserById($this->idUser);
        }
    }
    
}
