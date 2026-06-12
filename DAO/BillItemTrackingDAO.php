<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

/**
 * Description of BillItemTrackingDAO
 *
 * @author root
 */
class BillItemTrackingDAO {
    
    public static function create($description,$idSystemUser,$date,$idBillItem) {
        $id = Helpers::UUID();
        CloudEngineMySQLQuery::execute(new Connection(), "INSERT INTO BillItemTracking (idBillItemTracking,description,idSystemUser,createdTimestamp,idBillItem) VALUES (?,?,?,?,?);", array($id, $description,$idSystemUser,$date,$idBillItem));
        return $id;
    }
    
    public static function getByBillItem($item) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM BillItemTracking WHERE idBillItem = ? ORDER BY createdTimestamp DESC, autoincremet DESC;", array($item->getIdBillItem()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new BillItemTracking($row["idBillItemTracking"], $row["description"], $row["idSystemUser"], $row["createdTimestamp"], $row["idBillItem"]));
        }
        
        return $objects;
    }
    
    
}
