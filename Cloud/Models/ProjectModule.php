<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProjectModule
 *
 * @author root
 */
class ProjectModule {
    
    private $idProjectModule;
    
    private $name;
    
    private $idProject;
    
    public function __construct($idProjectModule, $name, $idProject) {
        $this->idProjectModule = $idProjectModule;
        $this->name = $name;
        $this->idProject = $idProject;
    }
    
    public function getIdProjectModule() {
        return $this->idProjectModule;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getIdProject() {
        return $this->idProject;
    }
    
    public function getRequirements() {
        return ProjectRequirementDAO::getRequirementsByModule($this);
    }
    
}
