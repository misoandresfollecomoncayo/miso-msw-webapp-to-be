<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class ProjectDAO {
    
    public static function getProjects() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Project ORDER BY name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Project($row["idProject"],$row["name"]));
        }
        
        return $objects;
    }
    
    public static function getProjectById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Project WHERE idProject = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Project($row["idProject"],$row["name"]);
        }
        
        return null;
    }
    
    public static function getProjectsByUser($user) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT P.* FROM Project P INNER JOIN User_Project UP ON UP.idProject = P.idProject WHERE UP.idUser = ? AND P.status = 'ACTIVE' ORDER BY P.name ASC;", array($user->getIdUser()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Project($row["idProject"],$row["name"]));
        }
        
        return $objects;
    }
    
    public static function getStartDate($project) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT R.startDate as 'date' FROM ProjectRequirement R INNER JOIN ProjectModule M ON M.idProjectModule = R.idProjectModule WHERE R.state != 'DELETED' AND M.idProject = ? ORDER BY R.startDate ASC LIMIT 1;", array($project->getIdProject()));
        while ($row = $query->fetch_assoc()) {
            return DateTime::createFromFormat("Y-m-d", $row["date"]);
        }
    }
    
    public static function getEndDate($project) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT R.endDate as 'date' FROM ProjectRequirement R INNER JOIN ProjectModule M ON M.idProjectModule = R.idProjectModule WHERE R.state != 'DELETED' AND M.idProject = ? ORDER BY R.endDate DESC LIMIT 1;", array($project->getIdProject()));
        while ($row = $query->fetch_assoc()) {
            return DateTime::createFromFormat("Y-m-d", $row["date"]);
        }
    }
    
}
