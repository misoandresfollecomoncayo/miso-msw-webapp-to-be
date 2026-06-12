<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class ProjectModuleDAO {
    
    public static function getProjectModuleById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ProjectModule WHERE idProjectModule = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new ProjectModule($row["idProjectModule"],$row["name"],$row["idProject"]);
        }
        
        return null;
    }
    
    public static function getProjectModulesByProjectId($idProject) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ProjectModule WHERE idProject = ? ORDER BY name ASC;", array($idProject));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ProjectModule($row["idProjectModule"],$row["name"],$row["idProject"]));
        }
        
        return $objects;
    }
    
}
