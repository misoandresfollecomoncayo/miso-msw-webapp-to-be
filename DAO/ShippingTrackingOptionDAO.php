<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class ShippingTrackingOptionDAO {
    
    public static function getShippingTrackingOptions() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ShippingTrackingOption ORDER BY `order` ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ShippingTrackingOption($row["idShippingTrackingOption"], $row["text"], $row["order"]));
        }
        
        return $objects;
    }
    
}
