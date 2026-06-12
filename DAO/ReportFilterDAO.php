<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class ReportFilterDAO {
    
    public static function getReportFiltersByReport($report) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ReportFilter WHERE idReport = ?;", array($report->getIdReport()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ReportFilter($row["idReportFilter"],$row["name"],$row["dataType"],$row["required"],$row["idReport"]));
        }
        
        return $objects;
    }
    
}
