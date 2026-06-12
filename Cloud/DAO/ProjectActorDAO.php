<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class ProjectActorDAO {
    
    public static function getActorById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ProjectActor WHERE idProjectActor = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new ProjectActor($row["idProjectActor"],$row["names"],$row["description"]);
        }
        
        return null;
    }
    
    public static function getActorsByProject($project) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT A.* FROM ProjectActor A INNER JOIN ProjectActor_Project AP ON AP.idProjectActor = A.idProjectActor WHERE AP.idProject = ? ORDER BY A.names ASC;", array($project->getIdProject()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ProjectActor($row["idProjectActor"],$row["names"],$row["description"]));
        }
        
        return $objects;
    }
    
}
