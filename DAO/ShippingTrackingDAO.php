<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class ShippingTrackingDAO {
    
    public static function create($date, $description, $idSystemUser, $type, $idShipping) {
        $UUID = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO ShippingTracking (idShippingTracking,description,idSystemUser,createdTimestamp,type,idShipping) VALUES (?,?,?,?,?,?);", array($UUID, $description, $idSystemUser, $date, $type, $idShipping));
        return $UUID;
    }
    
    public static function getTrackingsByShipping($shipping) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ShippingTracking WHERE idShipping = ? ORDER BY createdTimestamp DESC, autoincrement DESC;", array($shipping->getIdShipping()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new PurchaseTracking($row["idShippingTracking"],$row["description"],$row["idSystemUser"],$row["createdTimestamp"],$row["type"],$row["idShipping"]));
        }
        
        return $objects;
    }
    
}