<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CSSClass
 *
 * @author root
 */
class CSSClass {
    
    private $idCSSClass;
    
    private $name;
    
    private $description;
    
    private $idCSSFile;
    
    private $idCSSMediaQuery;
    
    public function __construct($idCSSClass, $name, $description, $idCSSFile, $idCSSMediaQuery) {
        $this->idCSSClass = $idCSSClass;
        $this->name = $name;
        $this->description = $description;
        $this->idCSSFile = $idCSSFile;
        $this->idCSSMediaQuery = $idCSSMediaQuery;
    }
    
    function getIdCSSClass() {
        return $this->idCSSClass;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getIdCSSFile() {
        return $this->idCSSFile;
    }
    
    public function getProperties() {
        return CSSPropertieDAO::getCSSPropertiesByCSSClass($this);
    }
    
    public function getIdCSSMediaQuery() {
        return $this->idCSSMediaQuery;
    }

}
