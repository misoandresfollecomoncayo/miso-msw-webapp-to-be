<?php

class Role {
   
    private $idRole;
    
    private $name;
    
    public function __construct($idRole, $name) {
        $this->idRole = $idRole;
        $this->name = $name;
    }
    
    public function getIdRole() {
        return $this->idRole;
    }

    public function getName() {
        return $this->name;
    }

    public function getPermissions() {
        return PermissionDAO::getPermissionsByRole($this);
    }
    
}
