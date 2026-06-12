<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class ArrivalAlertDAO {
    
    public static function create($trackingNumber, $purchase, $items, $idStore, $idCustomer) {
        $id = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO ArrivalAlert (idArrivalAlert,trackingNumber,purchase,items,idStore,idCustomer) VALUES (?,?,?,?,?,?);", array($id, $trackingNumber, $purchase, $items, $idStore, $idCustomer));
        return $id;
    }
    
    public static function getArrivalAlertsByIdCustomer($idCustomer) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ArrivalAlert WHERE idCustomer = ? ORDER BY createdTimestamp DESC;", array($idCustomer));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ArrivalAlert($row["idArrivalAlert"],$row["trackingNumber"],$row["purchase"],$row["items"],$row["idStore"],$row["status"],$row["idCustomer"],$row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function getArrivalAlerts() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ArrivalAlert ORDER BY createdTimestamp DESC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ArrivalAlert($row["idArrivalAlert"],$row["trackingNumber"],$row["purchase"],$row["items"],$row["idStore"],$row["status"],$row["idCustomer"],$row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function getArrivalAlertByTrackingNumber($trackingNumber) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ArrivalAlert WHERE trackingNumber = ?;", array($trackingNumber));
        while ($row = $query->fetch_assoc()) {
            return new ArrivalAlert($row["idArrivalAlert"],$row["trackingNumber"],$row["purchase"],$row["items"],$row["idStore"],$row["status"],$row["idCustomer"],$row["createdTimestamp"]);
        }
        
        return null;
    }
    
    public static function updateStatusById($id, $status) {
        CloudEngineMySQLQuery::execute(new Connection(), "UPDATE ArrivalAlert SET status = ? WHERE idArrivalAlert = ?;", array($status, $id));
    }
    
    public static function updateStatusByTrackingNumber($trackingNumber, $status) {
        CloudEngineMySQLQuery::execute(new Connection(), "UPDATE ArrivalAlert SET status = ? WHERE trackingNumber = ?;", array($status, $trackingNumber));
    }
    
}
