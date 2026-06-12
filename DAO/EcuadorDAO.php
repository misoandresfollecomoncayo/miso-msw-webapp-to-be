<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class EcuadorDAO {
    
    public static function getById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Ecuador WHERE idEcuador = ? LIMIT 1;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Ecuador($row["idEcuador"],$row["billNumber"],$row["customerNames"],$row["createdTimestamp"]);
        }
        
        return null;
    }
    
    public static function getEcuadorsList() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Ecuador ORDER BY createdTimestamp DESC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Ecuador($row["idEcuador"],$row["billNumber"],$row["customerNames"],$row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function create($bill, $customer) {
        $UUID = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO Ecuador (idEcuador,billNumber,customerNames) VALUES (?,?,?);", array($UUID, $bill, $customer));
        return $UUID;
    }
    
    public static function edit($id, $bill, $customer) {
        $UUID = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "UPDATE Ecuador SET billNumber = ?, customerNames = ? WHERE idEcuador = ?;", array($bill,$customer,$id));
        return $UUID;
    }
    
    public static function deleteItemsPending($idEcuador) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "DELETE FROM EcuadorItem WHERE idEcuador = ? AND status = 'PENDING';", array($idEcuador));
    }
    
    public static function insertItem($quantity, $sequence, $description, $idEcuador) {
        $UUID = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO EcuadorItem (id,quantity,sequence,description,idEcuador) VALUES (?,?,?,?,?);", array($UUID,$quantity,$sequence,$description,$idEcuador));
    }
    
    public static function reverseItem($id) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "UPDATE EcuadorItem SET status = 'PENDING' WHERE id = ?;", array($id));
    }
    
    public static function getItemsByList($id) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM EcuadorItem WHERE idEcuador=? ORDER BY autoincrement ASC;", array($id));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new EcuadorItem($row["id"],$row["autoincrement"],$row["quantity"],$row["sequence"],$row["description"],$row["status"]));
        }
        
        return $objects;
    }
    
    public static function process($id) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "UPDATE EcuadorItem SET status = 'COMPLETED' WHERE id=?;", array($id));
    }
    
    public static function delete($id) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "DELETE FROM Ecuador WHERE idEcuador = ?;", array($id));
    }
    
}
