<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class CSSClassDAO {
    
    public static function getCSSClassById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM CSSClass WHERE idCSSClass = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new CSSClass($row["idCSSClass"],$row["name"],$row["description"],$row["idCSSFile"],$row["idCSSMediaQuery"]);
        }
        
        return null;
    }
    
    public static function getCSSClassesByCSSFile($CSSFile) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM CSSClass WHERE idCSSFile = ? AND (idCSSMediaQuery IS NULL OR idCSSMediaQuery = '-1') ORDER BY name ASC;", array($CSSFile->getIdCSSFile()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new CSSClass($row["idCSSClass"],$row["name"],$row["description"],$row["idCSSFile"],$row["idCSSMediaQuery"]));
        }
        
        return $objects;
    }
    
    public static function getCSSClassesByCSSMediaQuery($CSSMediaQuery) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM CSSClass WHERE idCSSMediaQuery = ? ORDER BY name ASC;", array($CSSMediaQuery->getIdCSSMediaQuery()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new CSSClass($row["idCSSClass"],$row["name"],$row["description"],$row["idCSSFile"],$row["idCSSMediaQuery"]));
        }
        
        return $objects;
    }
    
    public static function addCSSClass($name, $description, $idCSSFile, $idCSSMediaQuery) {
        $id = Helpers::UUID();
        
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "CALL AddCSSClass(?,?,?,?,?);", array($id, $name, $description, $idCSSFile, $idCSSMediaQuery));
        
        return $id;
    }
    
    public static function editCSSClass($id, $name, $description, $idCSSMediaQuery) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "CALL EditCSSClass(?,?,?,?);", array($id, $name, $description,$idCSSMediaQuery));
        
        return $id;
    }
    
}
