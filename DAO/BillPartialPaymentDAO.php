<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class BillPartialPaymentDAO {
    
    public static function create($date, $amount, $idPaymentMethod, $idBill, $idUser) {
        $id = Helpers::UUID();
        CloudEngineMySQLQuery::execute(new Connection(), "INSERT INTO BillPartialPayment (idBillPartialPayment, `date`, amount, idPaymentMethod, idBill, idUser) VALUES (?,?,?,?,?,?);", array($id, $date, $amount, $idPaymentMethod, $idBill, $idUser));
        return $id;
    }
    
    public static function getPartialPaymentsByBill($bill) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM BillPartialPayment WHERE idBill = ? ORDER BY createdTimestamp DESC;", array($bill->getIdBill()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new BillPartialPayment($row["idBillPartialPayment"], $row["date"], $row["amount"], $row["idPaymentMethod"], $row["idBill"], $row["idUser"], $row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
}
