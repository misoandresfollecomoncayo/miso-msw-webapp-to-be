<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class RoleDAO {
    
    public static function getRoleById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Role WHERE idRole = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Role($row["idRole"], $row["name"]);
        }
        
        return null;
    }

    public static function getRoles() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Role ORDER BY name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Role($row["idRole"],$row["name"]));
        }
        
        return $objects;
    }
    
}
