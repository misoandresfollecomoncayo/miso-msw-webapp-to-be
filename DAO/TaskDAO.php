<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class TaskDAO {
    
    public static function getTasksByDate($date) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Task WHERE date = ? ORDER BY FIELD(status, 'PENDING', 'FINISHED'), highPriority DESC;", array($date));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Task($row["idTask"], $row["consecutive"], $row["title"], $row["description"], $row["highPriority"], $row["idCountry"], $row["date"], $row["status"], $row["idCreator"], $row["idProcessor"], $row["idCompleted"], $row["idWarehouse"]));
        }
        
        return $objects;
    }
    
    public static function create($title, $description, $priority, $idCountry, $idWarehouse, $date, $idUser) {
        $id = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO Task (idTask, title, description, highPriority, idCountry, idWarehouse, `date`, idCreator, idProcessor, idCompleted) VALUES (?,?,?,?,?,?,?,?,null,null);", array($id,$title,$description,$priority,$idCountry,$idWarehouse,$date,$idUser));
    }
    
    public static function update($id, $title, $description, $priority, $idCountry, $idWarehouse, $date, $status) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "UPDATE Task SET title=?, description=?, highPriority=?, idCountry=?, idWarehouse=?, date=?, status=? WHERE idTask=?;", array($title,$description,$priority,$idCountry,$idWarehouse,$date,$status,$id));
    }
    
    public static function process($idUser, $id) {
        $task = TaskDAO::getTaskById($id);
        $connection = new Connection();
        
        if ($task->getStatus() == Task::STATUS_PENDING) {
            CloudEngineMySQLQuery::execute($connection, "UPDATE Task SET status='PROCESS', idProcessor=? WHERE idTask=?;", array($idUser, $id));
        } else {
            CloudEngineMySQLQuery::execute($connection, "UPDATE Task SET status='FINISHED', idCompleted=? WHERE idTask=?;", array($idUser, $id));
        }
    }
    
    public static function getTaskById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Task WHERE idTask = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Task($row["idTask"], $row["consecutive"], $row["title"], $row["description"], $row["highPriority"], $row["idCountry"], $row["date"], $row["status"], $row["idCreator"], $row["idProcessor"], $row["idCompleted"], $row["idWarehouse"]);
        }
        
        return null;
    }
    
    public static function delete($id) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "DELETE FROM Task WHERE idTask=?;", array($id));
    }
    
}
