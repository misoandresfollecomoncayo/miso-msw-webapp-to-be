<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class TrackingDAO {
    
    public static function getTrackingByIdRegistry($id) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Tracking WHERE idRegistry = ? ORDER BY timestamp DESC;", array($id));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Tracking($row["idTracking"],$row["timestamp"],$row["action"],$row["beforeValue"],$row["idUser"],$row["idRegistry"]));
        }
        
        return $objects;
    }
    
    public static function getCreatedTrackingByIdRegistry($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Tracking WHERE idRegistry = ? AND action = 'CREATED';", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Tracking($row["idTracking"],$row["timestamp"],$row["action"],$row["beforeValue"],$row["idUser"],$row["idRegistry"]);
        }
        
        return null;
    }
    
}
