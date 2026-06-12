<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class CSSFileDAO {
    
    public static function getCSSFiles() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM CSSFile ORDER BY name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new CSSFile($row["idCSSFile"],$row["name"],$row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function getCSSFileById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM CSSFile WHERE idCSSFile = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new CSSFile($row["idCSSFile"],$row["name"],$row["createdTimestamp"]);
        }
        
        return null;
    }
    
}

