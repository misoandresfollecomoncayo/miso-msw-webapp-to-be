<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class CityDAO {
    
    public static function create($name, $idCountry) {
        $id = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO City (idCity, name, idCountry) VALUES (?,?,?);", array($id, $name, $idCountry));
        return $id;
    }
    
    public static function update($idCity, $name, $idCountry) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "UPDATE City SET name = ?, idCountry = ? WHERE idCity = ?;", array($name, $idCountry, $idCity));
    }
    
    public static function delete($id) {
        
    }
    
    public static function getCityById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM City WHERE idCity = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new City($row["idCity"],$row["name"],$row["idCountry"]);
        }
        
        return null;
    }
    
    public static function getCitiesByCountry($country) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM City WHERE idCountry = ? ORDER BY name ASC;", array($country->getIdCountry()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new City($row["idCity"],$row["name"],$row["idCountry"]));
        }
        
        return $objects;
    }
    
    public static function getCities() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM City ORDER BY idCountry, name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new City($row["idCity"],$row["name"],$row["idCountry"]));
        }
        
        return $objects;
    }
    
}