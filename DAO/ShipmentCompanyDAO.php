<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class ShipmentCompanyDAO {
    
    public static function getShipmentCompanies() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ShipmentCompany WHERE isDeleted = 0 ORDER BY name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ShipmentCompany($row["idShipmentCompany"],$row["name"]));
        }
        
        return $objects;
    }
    
    public static function getShipmentCompanyById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ShipmentCompany WHERE idShipmentCompany = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new ShipmentCompany($row["idShipmentCompany"], $row["name"]);
        }
        
        return null;
    }
    
    public static function delete($id) {
        CloudEngineMySQLQuery::execute(new Connection(), "UPDATE ShipmentCompany SET isDeleted = 1 WHERE idShipmentCompany = ?;", array($id));
    }
    
    public static function create($name) {
        CloudEngineMySQLQuery::execute(new Connection(), "INSERT INTO ShipmentCompany (name) VALUES (?);", array($name));
    }
    
    public static function edit($id, $name) {
        CloudEngineMySQLQuery::execute(new Connection(), "UPDATE ShipmentCompany SET name=? WHERE idShipmentCompany=?;", array($name, $id));
    }
    
}
