<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class BillTrackingDAO {
    
    public static function create($date, $description, $idSystemUser, $type, $idBill) {
        $UUID = Helpers::UUID();
        CloudEngineMySQLQuery::execute(new Connection(), "INSERT INTO BillTracking (idBillTracking,description,idSystemUser,createdTimestamp,type,idBill) VALUES (?,?,?,?,?,?);", array($UUID, $description, $idSystemUser, $date, $type, $idBill));
        return $UUID;
    }
    
    public static function getTrackingsByBill($bill) {
        $objects = array();
        
        $query = CloudEngineMySQLQuery::execute(new Connection(), "SELECT * FROM BillTracking WHERE idBill = ? ORDER BY description, createdTimestamp DESC;", array($bill->getIdBill()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new BillTracking($row["idBillTracking"],$row["description"],$row["idSystemUser"],$row["createdTimestamp"],$row["type"],$row["idBill"]));
        }
        
        return $objects;
    }
    
}