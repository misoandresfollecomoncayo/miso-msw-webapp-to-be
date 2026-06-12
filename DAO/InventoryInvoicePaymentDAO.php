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
class InventoryInvoicePaymentDAO {
    
    public static function create($amount, $idPaymentMethod, $idInventoryInvoice, $user, $date) {
        $id = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute(
                $connection,
                "INSERT INTO InventoryInvoicePayment (`id`,`amount`,`idPaymentMethod`,`idInventoryInvoice`,`user`,`date`) VALUES (?,?,?,?,?,?)",
                array($id, $amount, $idPaymentMethod, $idInventoryInvoice, $user, $date));
        return $id;
    }
    
    public static function getByIdInventoryInvoice($idInventoryInvoice) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM InventoryInvoicePayment WHERE idInventoryInvoice = ? ORDER BY createdTimestamp DESC;", array($idInventoryInvoice));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new InventoryInvoicePayment(
                $row["id"],
                $row["amount"],
                $row["idPaymentMethod"],
                $row["idInventoryInvoice"],
                $row["user"],
                $row["createdTimestamp"],
                $row["date"])
            );
        }
        
        return $objects;
    }
    
}
