<?php

class DocumentType {
    
    private $idDocumentType;
    
    private $name;
    
    public function __construct($idDocumentType, $name) {
        $this->idDocumentType = $idDocumentType;
        $this->name = $name;
    }
    
    public function getIdDocumentType() {
        return $this->idDocumentType;
    }

    public function getName() {
        return $this->name;
    }
    
}