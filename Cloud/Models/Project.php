<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Project
 *
 * @author root
 */
class Project {
    
    private $idProject;
    
    private $name;
    
    public function __construct($idProject, $name) {
        $this->idProject = $idProject;
        $this->name = $name;
    }
    
    public function getIdProject() {
        return $this->idProject;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getModules() {
        return ProjectModuleDAO::getProjectModulesByProjectId($this->getIdProject());
    }
    
    public function getActors() {
        return ProjectActorDAO::getActorsByProject($this);
    }
    
    public function getRequirementsByPriority() {
        return ProjectRequirementDAO::getRequirementsByPriority($this);
    }
    
    public function getCompletedRequirements() {
        $completed = array();
        $requirements = $this->getRequirementsByPriority();
        
        foreach ($requirements as $r) {
            if ($r->getState() == ProjectRequirement::STATE_COMPLETED) {
                array_push($completed, $r);
            }
        }
        
        return $completed;
    }
    
    public function getRequirementsByDateAsc() {
        return ProjectRequirementDAO::getRequirementsByDateAsc($this);
    }
    
    public function getCompletedPercent() {
        $totalRequirements = count($this->getRequirementsByPriority());
        $completedRequirements = count($this->getCompletedRequirements());
        return $totalRequirements > 0 ? round(($completedRequirements * 100) / $totalRequirements,1) : 0;
    }
    
    public function getStartDate() {
        return ProjectDAO::getStartDate($this);
    }
    
    public function getEndDate() {
        return ProjectDAO::getEndDate($this);
    }
    
    public function getRequirementsDatesRange() {
        return new DatePeriod($this->getStartDate(), new DateInterval('P1D'), $this->getEndDate()->modify("+1 day"));
    }
    
}
