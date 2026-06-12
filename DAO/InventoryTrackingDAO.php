<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InventoryTrackingDAO
 *
 * @author andres
 */
class InventoryTrackingDAO {
    
    public static function create($date, $detail, $idInventory, $user) {
        $id = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute(
                $connection,
                "INSERT INTO InventoryTracking (`id`,`detail`,`idInventory`,`user`,`createdTimestamp`) VALUES (?,?,?,?,?)",
                array($id, $detail, $idInventory, $user, $date . " 00:00:00"));
        return $id;
    }
    
    public static function getByIdInventory($idInventory) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM InventoryTracking WHERE idInventory = ? ORDER BY `createdTimestamp` DESC, `number` DESC;", array($idInventory));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new InventoryTracking(
                $row["id"],
                $row["detail"],
                $row["idInventory"],
                $row["user"],
                $row["createdTimestamp"])
            );
        }
        
        return $objects;
    }
    
}
