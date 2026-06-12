<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CSSPropertieDAO
 *
 * @author root
 */
class CSSPropertieDAO {
    
    public static function getCSSPropertiesByCSSClass($class) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM CSSPropertie WHERE idCSSClass = ? ORDER BY propertieKey ASC;", array($class->getIdCSSClass()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new CSSPropertie($row["idCSSPropertie"],$row["propertieKey"],$row["propertieValue"],$row["idCSSClass"]));
        }
        
        return $objects;
    }
    
    public static function addCSSPropertie($key, $value, $idCSSClass) {
        $id = Helpers::UUID();
        
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "CALL AddCSSPropertie(?,?,?,?);", array($id, $key, $value, $idCSSClass));
        
        return $id;
    }
    
}
