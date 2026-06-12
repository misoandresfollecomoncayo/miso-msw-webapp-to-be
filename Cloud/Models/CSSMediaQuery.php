<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CSSMediaQuery
 *
 * @author root
 */
class CSSMediaQuery {
    
    private $idCSSMediaQuery;
    
    private $query;
    
    private $idCSSFile;
    
    public function __construct($idCSSMediaQuery, $query, $idCSSFile) {
        $this->idCSSMediaQuery = $idCSSMediaQuery;
        $this->query = $query;
        $this->idCSSFile = $idCSSFile;
    }

    public function getIdCSSMediaQuery() {
        return $this->idCSSMediaQuery;
    }

    public function getQuery() {
        return $this->query;
    }

    public function getIdCSSFile() {
        return $this->idCSSFile;
    }
    
    public function getClasses() {
        return CSSClassDAO::getCSSClassesByCSSMediaQuery($this);
    }
    
}
