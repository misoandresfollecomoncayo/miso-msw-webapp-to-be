<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class PurchasePictureDAO {
    
    public static function create($idPurchasePicture, $idPurchase) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "INSERT INTO PurchasePicture (idPurchasePicture, idPurchase) VALUES (?,?);", array($idPurchasePicture, $idPurchase));
    }
    
    public static function getPurchasePicturesByPurchase($purchase) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM PurchasePicture WHERE idPurchase = ?;", array($purchase->getIdPurchase()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new PurchasePicture($row["idPurchasePicture"],$row["idPurchase"]));
        }
        
        return $objects;
    }
    
    public static function delete($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "DELETE FROM PurchasePicture WHERE idPurchasePicture=?;", array($id));
    }
    
}