<?php

class Access {
    
    const TYPE_SYSTEM_USER = "SystemUser";
    
    const TYPE_CUSTOMER = "Customer";
    
    private $idRegister;
    
    private $email;
    
    private $password;
    
    private $active;
    
    private $deleted;
    
    private $type;
    
    public function __construct($idRegister, $email, $password, $active, $deleted, $type) {
        $this->idRegister = $idRegister;
        $this->email = $email;
        $this->password = $password;
        $this->active = $active;
        $this->deleted = $deleted;
        $this->type = $type;
    }
    
    function getIdRegister() {
        return $this->idRegister;
    }

    function getEmail() {
        return $this->getObject()->getEmail();
    }

    function getPassword() {
        return $this->getObject()->getPassword();
    }

    function isActive() {
        return $this->getObject()->isActive();
    }

    function isDeleted() {
        return $this->getObject()->isDeleted();
    }

    function getType() {
        return $this->type;
    }

    function getObject() {
        if ($this->getType() == Access::TYPE_CUSTOMER) {
            return CustomerDAO::getCustomerById($this->idRegister);
        } else {
            return SystemUserDAO::getSystemUserById($this->idRegister);
        }
    }
    
    function updatePassword($new) {
        if ($this->getType() == Access::TYPE_CUSTOMER) {
            CustomerDAO::updatePassword($this->idRegister, $new);
        } else {
            SystemUserDAO::updatePassword($this->idRegister, $new);
        }
    }
    
    public function hasPermission($permission) {
        $permissions = $this->getObject()->getRole()->getPermissions();
        
        foreach ($permissions as $p) {
            if ($p->getName() == $permission) {
                return true;
            }
        }
        
        return false;
    }
    
}
