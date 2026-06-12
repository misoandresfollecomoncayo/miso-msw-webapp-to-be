<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class SystemUserDAO {
    
    public static function create($names, $email, $password, $idRole, $requestShipmentNotification, $alertArrivalNotification) {
        $access = AccessDAO::getAccessByEmail($email);
        
        if ($access == null) {
            $UUID = Helpers::UUID();
            
            $connection = new Connection();
            $query = CloudEngineMySQLQuery::execute($connection, "INSERT INTO SystemUser (idSystemUser,names,email,password,idRole,requestShipmentNotification,alertArrivalNotification) VALUES (?,?,?,?,?,?,?);", array($UUID, $names, $email, md5($password), $idRole, $requestShipmentNotification, $alertArrivalNotification));
            
            return $UUID;
        } else {
            throw new Exception("Ya existe un acceso con el mismo correo electrónico.");
        }
    }
    
    public static function update($idSystemUser, $names, $email, $idRole, $requestShipmentNotification, $alertArrivalNotification) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE SystemUser SET names=?,email=?,idRole=?,requestShipmentNotification=?,alertArrivalNotification=? WHERE idSystemUser=?;", array($names,$email,$idRole,$requestShipmentNotification,$alertArrivalNotification,$idSystemUser));
    }
    
    public static function active($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE SystemUser SET active = 1 WHERE idSystemUser = ?;", array($id));
    }
    
    public static function deactivate($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE SystemUser SET active = 0 WHERE idSystemUser = ?;", array($id));
    }
    
    public static function delete($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE SystemUser SET deleted = 1 WHERE idSystemUser = ?;", array($id));
    }
    
    public static function getSystemUserById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM SystemUser WHERE idSystemUser = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new SystemUser($row["idSystemUser"],$row["names"],$row["email"],$row["password"],$row["idRole"],$row["requestShipmentNotification"],$row["alertArrivalNotification"],$row["active"],$row["deleted"],$row["createdTimestamp"]);
        }
        
        return null;
    }
    
    public static function getSystemUsers() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM SystemUser WHERE deleted = 0 ORDER BY names ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new SystemUser($row["idSystemUser"],$row["names"],$row["email"],$row["password"],$row["idRole"],$row["requestShipmentNotification"],$row["alertArrivalNotification"],$row["active"],$row["deleted"],$row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function getSystemUsersSendRequestShipmentNotification() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM SystemUser WHERE requestShipmentNotification = 1 AND active = 1 AND deleted = 0 ORDER BY names ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new SystemUser($row["idSystemUser"],$row["names"],$row["email"],$row["password"],$row["idRole"],$row["requestShipmentNotification"],$row["alertArrivalNotification"],$row["active"],$row["deleted"],$row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function getSystemUsersSendAlertArrivalNotification() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM SystemUser WHERE alertArrivalNotification = 1 AND active = 1 AND deleted = 0 ORDER BY names ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new SystemUser($row["idSystemUser"],$row["names"],$row["email"],$row["password"],$row["idRole"],$row["requestShipmentNotification"],$row["alertArrivalNotification"],$row["active"],$row["deleted"],$row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function updatePassword($id, $new) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE SystemUser SET password = ? WHERE idSystemUser = ?;", array(md5($new), $id));
        
        // Insert notification
        NotificationDAO::create("Clave actualizada.", $id);
    }
    
}
