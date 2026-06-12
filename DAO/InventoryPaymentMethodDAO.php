<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class InventoryPaymentMethodDAO {
    
    public static function getById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM InventoryPaymentMethod WHERE id = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new InventoryPaymentMethod($row["id"],$row["name"]);
        }
        
        return null;
    }
    
    public static function getAll() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM InventoryPaymentMethod ORDER BY name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new InventoryPaymentMethod($row["id"],$row["name"]));
        }
        
        return $objects;
    }
    
}