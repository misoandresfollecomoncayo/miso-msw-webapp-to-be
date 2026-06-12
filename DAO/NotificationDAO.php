<?php

use \Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use \Cloud\Engine\PHP\MySQL\Helpers;

class NotificationDAO {
    
    public static function create($content, $idUser) {
        $UUID = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO Notification (idNotification, content, idUser) VALUES (?,?,?);", array($UUID, $content, $idUser));
        return $UUID;
    }
    
    public static function getUnreadNotificationsByIdUser($idUser) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Notification WHERE idUser = ? AND viewed = 0 ORDER BY createdTimestamp DESC;", array($idUser));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Notification($row["idNotification"],$row["content"],$row["viewed"],$row["idUser"],$row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function getNotificationsByIdUser($idUser) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Notification WHERE idUser = ? ORDER BY createdTimestamp DESC;", array($idUser));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Notification($row["idNotification"],$row["content"],$row["viewed"],$row["idUser"],$row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function process($idNotification) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "UPDATE Notification SET viewed = 1 WHERE idNotification = ?;", array($idNotification));
    }
    
    public static function delete($idNotification) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "DELETE FROM Notification WHERE idNotification = ?;", array($idNotification));
    }
    
}