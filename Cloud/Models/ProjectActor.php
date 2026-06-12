<?php

class ProjectActor {
    
    private $idProjectActor;
    
    private $names;
    
    private $description;
    
    public function __construct($idProjectActor, $names, $description) {
        $this->idProjectActor = $idProjectActor;
        $this->names = $names;
        $this->description = $description;
    }
    
    public function getIdProjectActor() {
        return $this->idProjectActor;
    }
    
    public function getNames() {
        return $this->names;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
}
