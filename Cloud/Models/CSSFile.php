<?php

class CSSFile {
    
    private $idCSSFile;
    
    private $name;
    
    private $createdTimestamp;
    
    public function __construct($idCSSFile, $name, $createdTimestamp) {
        $this->idCSSFile = $idCSSFile;
        $this->name = $name;
        $this->createdTimestamp = $createdTimestamp;
    }
    
    public function getIdCSSFIle() {
        return $this->idCSSFile;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }
    
    public function getClasses() {
        return CSSClassDAO::getCSSClassesByCSSFile($this);
    }
    
    public function getMediaQueries() {
        return CSSMediaQueryDAO::getCSSMediaQueriesByCSSFile($this);
    }
    
}
