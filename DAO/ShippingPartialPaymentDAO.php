<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class ShippingPartialPaymentDAO {
    
    public static function create($date, $amount, $idPaymentMethod, $idShipping, $idUser) {
        $id = Helpers::UUID();
        CloudEngineMySQLQuery::execute(new Connection(), "INSERT INTO ShippingPartialPayment (idShippingPartialPayment, `date`, amount, idPaymentMethod, idShipping, idUser) VALUES (?,?,?,?,?,?);", array($id, $date, $amount, $idPaymentMethod, $idShipping, $idUser));
        return $id;
    }
    
    public static function getPartialPaymentsByShipping($shipping) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ShippingPartialPayment WHERE idShipping = ? ORDER BY createdTimestamp DESC;", array($shipping->getIdShipping()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ShippingPartialPayment($row["idShippingPartialPayment"], $row["date"], $row["amount"], $row["idPaymentMethod"], $row["idShipping"], $row["idUser"], $row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
}
