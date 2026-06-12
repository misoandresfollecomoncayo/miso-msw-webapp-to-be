<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class ProjectRequirementDAO {
    
    public static function getRequirementById($id, $throwException = false) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "CALL SelectProjectRequirementById(?);", array($id));
        while ($row = $query->fetch_assoc()) {
            return new ProjectRequirement($row["idProjectRequirement"],$row["description"],$row["idProjectModule"],$row["state"],$row["completedTimestamp"],$row["completedIdUser"],$row["idProjectActor"],$row["priority"],$row["complexity"],$row["startDate"],$row["endDate"]);
        }
        
        return null;
    }
    
    public static function getRequirementsByModule($module) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "CALL SelectProjectRequirementsByIdModule(?);", array($module->getIdProjectModule()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ProjectRequirement($row["idProjectRequirement"],$row["description"],$row["idProjectModule"],$row["state"],$row["completedTimestamp"],$row["completedIdUser"],$row["idProjectActor"],$row["priority"],$row["complexity"],$row["startDate"],$row["endDate"]));
        }
        
        return $objects;
    }
    
    public static function getRequirementsByPriority($project) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "CALL SelectProjectRequirementsByPriority(?);", array($project->getIdProject()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ProjectRequirement($row["idProjectRequirement"],$row["description"],$row["idProjectModule"],$row["state"],$row["completedTimestamp"],$row["completedIdUser"],$row["idProjectActor"],$row["priority"],$row["complexity"],$row["startDate"],$row["endDate"]));
        }
        
        return $objects;
    }
    
    public static function getRequirementsByDateAsc($project) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "CALL SelectProjectRequirementsByDateAsc(?);", array($project->getIdProject()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ProjectRequirement($row["idProjectRequirement"],$row["description"],$row["idProjectModule"],$row["state"],$row["completedTimestamp"],$row["completedIdUser"],$row["idProjectActor"],$row["priority"],$row["complexity"],$row["startDate"],$row["endDate"]));
        }
        
        return $objects;
    }
    
    public static function add($description, $idProjectModule, $idProjectActor, $priority, $complexity, $startDate, $endDate, $createdIdUser) {
        $id = Helpers::UUID();
        
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "CALL AddProjectRequirement(?,?,?,?,?,?,?,?,?);", array($id, $description, $idProjectModule, $idProjectActor, $priority, $complexity, $startDate, $endDate, $createdIdUser));
        
        return $id;
    }
    
    public static function edit($id, $description, $idProjectModule, $state, $idActor, $priority, $complexity, $startDate, $endDate, $idUser) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "CALL EditProjectRequirement(?,?,?,?,?,?,?,?,?,?);", array($id, $description, $idProjectModule, $state, $idActor, $priority, $complexity, $startDate, $endDate, $idUser));
    }
    
    public static function delete($id, $idUser) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "CALL DeleteProjectRequirement(?,?);", array($id, $idUser));
    }
    
    public static function complete($id, $idUser) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "CALL CompleteProjectRequirement(?,?);", array($id, $idUser));
    }
    
}
