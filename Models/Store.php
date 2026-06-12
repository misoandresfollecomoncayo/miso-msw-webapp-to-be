<?php

class Store {
    
    private $idStore;
    
    private $name;

    private $website;

    private $deleted;
    
    public function __construct($idStore, $name, $website, $deleted) {
        $this->idStore = $idStore;
        $this->name = $name;
        $this->website = $website;
        $this->deleted = $deleted;
    }
    
    public function getIdStore() {
        return $this->idStore;
    }

    public function getName() {
        return $this->name;
    }

    public function getWebsite() {
        return $this->website;
    }

    public function isDeleted() {
        return $this->deleted;
    }
    
}