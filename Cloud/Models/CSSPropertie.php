<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CSSPropertie
 *
 * @author root
 */
class CSSPropertie {
    
    private $idCSSPropertie;
    
    private $propertieKey;
    
    private $propertieValue;
    
    private $idCSSClass;
    
    function __construct($idCSSPropertie, $key, $value, $idCSSClass) {
        $this->idCSSPropertie = $idCSSPropertie;
        $this->propertieKey = $key;
        $this->propertieValue = $value;
        $this->idCSSClass = $idCSSClass;
    }

    function getIdCSSPropertie() {
        return $this->idCSSPropertie;
    }

    function getKey() {
        return $this->propertieKey;
    }

    function getValue() {
        return $this->propertieValue;
    }

    function getIdCSSClass() {
        return $this->idCSSClass;
    }
    
}
