<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class CSSMediaQueryDAO {
    
    public static function getCSSMediaQueriesByCSSFile($CSSFile) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM CSSMediaQuery WHERE idCSSFile = ? ORDER BY query ASC;", array($CSSFile->getIdCSSFile()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new CSSMediaQuery($row["idCSSMediaQuery"],$row["query"],$row["idCSSFile"]));
        }
        
        return $objects;
    }
    
}
