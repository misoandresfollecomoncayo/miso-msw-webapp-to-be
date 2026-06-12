<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class PurchaseTrackingDAO {
    
    public static function create($description, $idSystemUser, $type, $idPurchase) {
        $UUID = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO PurchaseTracking (idPurchaseTracking,description,idSystemUser,type,idPurchase) VALUES (?,?,?,?,?);", array($UUID, $description, $idSystemUser, $type, $idPurchase));
        return $UUID;
    }
    
    public static function getTrackingsByPurchase($purchase) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM PurchaseTracking WHERE idPurchase = ? ORDER BY createdTimestamp DESC;", array($purchase->getIdPurchase()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new PurchaseTracking($row["idPurchaseTracking"],$row["description"],$row["idSystemUser"],$row["createdTimestamp"],$row["type"],$row["idPurchase"]));
        }
        
        return $objects;
    }
    
}
