<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class StoreDAO {
    
    public static function create($idStore, $name, $website) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "INSERT INTO Store(idStore, name, website) VALUES (?,?,?);", array($idStore,$name,$website));
    }
    
    public static function update($idStore, $name, $website) {
        
    }
    
    public static function delete($id) {
        
    }
    
    public static function getStoreById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Store WHERE idStore = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Store($row["idStore"],$row["name"],$row["website"],$row["deleted"]);
        }
        
        return null;
    }
    
    public static function getStoreByName($name) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Store WHERE name = ?;", array($name));
        while ($row = $query->fetch_assoc()) {
            return new Store($row["idStore"],$row["name"],$row["website"],$row["deleted"]);
        }
        
        return null;
    }
    
    public static function getStores() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Store ORDER BY name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Store($row["idStore"],$row["name"],$row["website"],$row["deleted"]));
        }
        
        return $objects;
    }
    
}