<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class BillItemDAO {
    
    public static function getBillItemsByBill($bill) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM BillItem WHERE idBill = ? ORDER BY autoincrement ASC;", array($bill->getIdBill()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new BillItem($row["idBillItem"], $row["description"], $row["boxNumber"], $row["weight"], $row["amount"], $row["idBill"], $row["delivered"]));
        }
        
        return $objects;
    }
    
    public static function create($description, $boxNumber, $weight, $amount, $idBill) {
        $id = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO BillItem (idBillItem, description, boxNumber, weight, amount, idBill) VALUES (?,?,?,?,?,?);", array($id, $description, $boxNumber, $weight, $amount, $idBill));
        return $id;
    }
    
    public static function update($id, $description, $boxNumber, $weight, $amount) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "UPDATE BillItem SET description=?,boxNumber=?,weight=?,amount=? WHERE idBillItem = ?;", array($description,$boxNumber,$weight,$amount,$id));
        return $id;
    }
    
    public static function delete($id) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "DELETE FROM BillItem WHERE idBillItem=?", array($id));
    }
    
    public static function deleteByBill($idBill) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "DELETE FROM BillItem WHERE idBill=?", array($idBill));
    }
    
    public static function deliver($id) {
        CloudEngineMySQLQuery::execute(new Connection(), "UPDATE BillItem SET delivered = 1 WHERE idBillItem=?", array($id));
    }
    
}
