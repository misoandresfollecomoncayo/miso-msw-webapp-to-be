<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class ReportDAO {
    
    public static function getReports() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Report ORDER BY name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Report($row["idReport"],$row["name"],$row["query"]));
        }
        
        return $objects;
    }
    
    public static function getReportById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Report WHERE idReport = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Report($row["idReport"],$row["name"],$row["query"]);
        }
        
        return null;
    }
    
}
