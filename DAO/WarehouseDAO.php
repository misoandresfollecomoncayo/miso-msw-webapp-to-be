<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class WarehouseDAO {
    
    public static function getWarehouseById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Warehouse WHERE idWarehouse = ? LIMIT 1;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Warehouse($row["idWarehouse"],$row["name"]);
        }
        
        return null;
    }
    
    public static function getWarehouses() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Warehouse ORDER BY name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Warehouse($row["idWarehouse"],$row["name"]));
        }
        
        return $objects;
    }
    
}
