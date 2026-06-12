<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class CountryDAO {
    
    public static function create($name) {
        
    }
    
    public static function update($idCountry, $name) {
        
    }
    
    public static function delete($id) {
        
    }
    
    public static function getCountryById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Country WHERE idCountry = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Country($row["idCountry"],$row["name"]);
        }
        
        return null;
    }
    
    public static function getCountries() {   
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Country ORDER BY name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Country($row["idCountry"],$row["name"]));
        }
        
        return $objects;
    }
    
}