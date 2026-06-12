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
class InventoryInvoiceTrackingDAO {
    
    public static function create($date, $detail, $idInventoryInvoice, $user) {
        try {
            $id = Helpers::UUID();
            $connection = new Connection();
            CloudEngineMySQLQuery::execute(
                    $connection,
                    "INSERT INTO InventoryInvoiceTracking (`id`,`detail`,`idInventoryInvoice`,`user`,`createdTimestamp`,`number`) VALUES (?,?,?,?,?,?)",
                    array($id, $detail, $idInventoryInvoice, $user, $date . " " . date("h:i:s"), null));
            return $id;
        } catch(Exception $e) {
            throw $e;
        }
    }
    
    public static function getByIdInventoryInvoice($idInventoryInvoice) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM InventoryInvoiceTracking WHERE idInventoryInvoice = ? ORDER BY `createdTimestamp` DESC, `number` DESC;", array($idInventoryInvoice));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new InventoryTracking(
                $row["id"],
                $row["detail"],
                $row["idInventoryInvoice"],
                $row["user"],
                $row["createdTimestamp"])
            );
        }
        
        return $objects;
    }
    
}
