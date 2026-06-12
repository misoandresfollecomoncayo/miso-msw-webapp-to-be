<?php

class User {
    
    private $idUser;
    
    private $names;
    
    private $email;
    
    private $password;
    
    private $idRole;
    
    public function __construct($idUser, $names, $email, $password, $idRole) {
        $this->idUser = $idUser;
        $this->names = $names;
        $this->email = $email;
        $this->password = $password;
        $this->idRole = $idRole;
    }
    
    public function getIdUser() {
        return $this->idUser;
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
    
    public function getProjects() {
        return ProjectDAO::getProjectsByUser($this);
    }
    
    public function getRole() {
        return RoleDAO::getRoleById($this->idRole);
    }
    
    public function hasPermission($name) {
        $permissions = $this->getRole()->getPermissions();
        
        foreach ($permissions as $p) {
            if ($p->getName() == $name) {
                return true;
            }
        }
        
        return false;
    }
    
}