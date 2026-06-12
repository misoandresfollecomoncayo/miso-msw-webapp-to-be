<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class UserDAO {
    
    public static function getUserById($id, $throwException = false) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM User WHERE idUser = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new User($row["idUser"],$row["names"],$row["email"],$row["password"],$row["idRole"]);
        }
        
        if (true == $throwException) {
            throw new Exception("El usuario buscado no existe.");
        } else {
            return null;
        }
    }
    
    public static function getUserByEmail($email, $throwException = false) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM User WHERE email = ?;", array($email));
        while ($row = $query->fetch_assoc()) {
            return new User($row["idUser"],$row["names"],$row["email"],$row["password"],$row["idRole"]);
        }
        
        if (true == $throwException) {
            throw new Exception("El usuario buscado no existe.");
        } else {
            return null;
        }
    }
    
    public static function getUsers() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM User ORDER BY names ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new User($row["idUser"],$row["names"],$row["email"],$row["password"],$row["idRole"]));
        }
        
        return $objects;
    }
    
    public static function changePassword($idUser, $newPassword) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "CALL UpdateUserPassword(?,?);", array($idUser, $newPassword));
    }
    
}
