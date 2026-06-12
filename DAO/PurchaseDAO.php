<?php

use \Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use \Cloud\Engine\PHP\MySQL\Helpers;

class PurchaseDAO {
    
    public static function create($content, $netWeight, $long, $width, $high, $idCustomer, $trackingNumber, $idStore, $quantity, $idSystemUser, $date) {
        $UUID = Helpers::UUID();
        $query = CloudEngineMySQLQuery::execute(new Connection(), "INSERT INTO Purchase (idPurchase,content,netWeight,longValue,width,high,idCustomer,trackingNumber,idStore,quantity,createdTimestamp) VALUES (?,?,?,?,?,?,?,?,?,?,?);", array($UUID, $content, $netWeight, $long, $width, $high, $idCustomer, $trackingNumber, $idStore, $quantity, $date));

        // Insert tracking
        PurchaseTrackingDAO::create("Mercancía ingresada a bodega.", $idSystemUser, PurchaseTracking::TYPE_PUBLIC, $UUID);
        
        // Insert notification
        NotificationDAO::create("Nueva compra registrada en bodega: " . $content, $idCustomer);

        return $UUID;
    }
    
    public static function update($idPurchase, $date, $content, $netWeight, $long, $width, $high, $idCustomer, $trackingNumber, $idStore, $quantity, $idSystemUser) {
        $query = CloudEngineMySQLQuery::execute(new Connection(), "UPDATE Purchase SET createdTimestamp=?, content=?, netWeight=?, longValue=?, width=?, high=?, idCustomer=?, trackingNumber=?, idStore=?, quantity=? WHERE idPurchase=?;", array($date, $content, $netWeight, $long, $width, $high, $idCustomer, $trackingNumber, $idStore, $quantity, $idPurchase));
        
        // Insert tracking
        PurchaseTrackingDAO::create("Información editada.", $idSystemUser, PurchaseTracking::TYPE_PUBLIC, $idPurchase);
    }

    public static function updateNetWeight($idPurchase, $netWeight) {
        $query = CloudEngineMySQLQuery::execute(new Connection(), "UPDATE Purchase SET netWeight=? WHERE idPurchase=?;", array($netWeight, $idPurchase));
        
        // Insert tracking
        PurchaseTrackingDAO::create("Peso neto cambiado.", $idSystemUser, PurchaseTracking::TYPE_PRIVATE, $idPurchase);
    }
    
    public static function delete($id) {
        CloudEngineMySQLQuery::execute(new Connection(), "DELETE FROM Purchase WHERE idPurchase=?;", array($id));
    }
    
    public static function setShipping($idPurchase, $idShipping, $idSystemUser) {
        $shipping = ShippingDAO::getShippingById($idShipping);
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE Purchase SET idShipping=? WHERE idPurchase=?;", array($idShipping, $idPurchase));
        
        // Insert tracking
        PurchaseTrackingDAO::create("Agregado a envío no. " . $shipping->getShippingNumber(), $idSystemUser, PurchaseTracking::TYPE_PUBLIC, $idPurchase);
    }
    
    public static function request($id) {
        CloudEngineMySQLQuery::execute(new Connection(), "UPDATE Purchase SET requested = 1 WHERE idPurchase=?;", array($id));
    }

    public static function getPurchaseById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Purchase WHERE idPurchase = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Purchase($row["idPurchase"], $row["content"], $row["netWeight"], $row["longValue"], $row["width"], $row["high"], $row["idCustomer"], $row["trackingNumber"], $row["idStore"], $row["quantity"], $row["idShipping"], $row["createdTimestamp"], $row["requested"]);
        }
        
        return null;
    }
    
    public static function getPurchaseByTrackingNumber($trackingNumber) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Purchase WHERE trackingNumber = ?;", array($trackingNumber));
        while ($row = $query->fetch_assoc()) {
            return new Purchase($row["idPurchase"], $row["content"], $row["netWeight"], $row["longValue"], $row["width"], $row["high"], $row["idCustomer"], $row["trackingNumber"], $row["idStore"], $row["quantity"], $row["idShipping"], $row["createdTimestamp"], $row["requested"]);
        }
        
        return null;
    }
    
    public static function getPurchasesByShipping($shipping) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Purchase WHERE idShipping = ? ORDER BY createdTimestamp DESC;", array($shipping->getIdShipping()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Purchase($row["idPurchase"], $row["content"], $row["netWeight"], $row["longValue"], $row["width"], $row["high"], $row["idCustomer"], $row["trackingNumber"], $row["idStore"], $row["quantity"], $row["idShipping"], $row["createdTimestamp"], $row["requested"]));
        }
        
        return $objects;
    }
    
    public static function getPurchasesByCustomer($customer) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Purchase WHERE idCustomer = ? ORDER BY createdTimestamp DESC;", array($customer->getIdCustomer()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Purchase($row["idPurchase"], $row["content"], $row["netWeight"], $row["longValue"], $row["width"], $row["high"], $row["idCustomer"], $row["trackingNumber"], $row["idStore"], $row["quantity"], $row["idShipping"], $row["createdTimestamp"], $row["requested"]));
        }
        
        return $objects;
    }
    
    public static function getPendingPurchasesByCustomer($customer) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Purchase WHERE idCustomer = ? AND idShipping IS NULL ORDER BY createdTimestamp DESC;", array($customer->getIdCustomer()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Purchase($row["idPurchase"], $row["content"], $row["netWeight"], $row["longValue"], $row["width"], $row["high"], $row["idCustomer"], $row["trackingNumber"], $row["idStore"], $row["quantity"], $row["idShipping"], $row["createdTimestamp"], $row["requested"]));
        }
        
        return $objects;
    }
    
    public static function getPurchasesDataTables($start, $length, $search) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection,
            "SELECT
                P.idPurchase,P.content,P.netWeight,P.netWeight,P.longValue,P.width,P.high,P.idCustomer,P.trackingNumber,P.idStore,P.quantity,P.idShipping,P.createdTimestamp,P.requested
            FROM
                Purchase P
                INNER JOIN Customer C ON C.idCustomer = P.idCustomer
            WHERE
                P.trackingNumber LIKE CONCAT('%',?,'%')
                OR P.content LIKE CONCAT('%',?,'%')
                OR P.createdTimestamp LIKE CONCAT('%',?,'%')
                OR C.lockerNumber LIKE CONCAT('%',?,'%')
                OR C.names LIKE CONCAT('%',?,'%')
            ORDER BY
                P.createdTimestamp DESC, P.autoincrement DESC LIMIT ?, ?;",
            array($search, $search, $search, $search, $search, $start, $length));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Purchase($row["idPurchase"], $row["content"], $row["netWeight"], $row["longValue"], $row["width"], $row["high"], $row["idCustomer"], $row["trackingNumber"], $row["idStore"], $row["quantity"], $row["idShipping"], $row["createdTimestamp"], $row["requested"]));
        }
        
        return $objects;
    }
    
    public static function getCustomerPurchasesDataTables($idCustomer, $start, $length, $search) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection,
            "SELECT
                P.idPurchase,P.content,P.netWeight,P.netWeight,P.longValue,P.width,P.high,P.idCustomer,P.trackingNumber,P.idStore,P.quantity,P.idShipping,P.createdTimestamp,P.requested
            FROM
                Purchase P
                INNER JOIN Customer C ON C.idCustomer = P.idCustomer
            WHERE
                C.idCustomer = ?
                AND (P.trackingNumber LIKE CONCAT('%',?,'%')
                OR P.content LIKE CONCAT('%',?,'%')
                OR P.createdTimestamp LIKE CONCAT('%',?,'%')
                OR C.lockerNumber LIKE CONCAT('%',?,'%')
                OR C.names LIKE CONCAT('%',?,'%'))
            ORDER BY
                P.createdTimestamp DESC, P.autoincrement DESC LIMIT ?, ?;",
            array($idCustomer, $search, $search, $search, $search, $search, $start, $length));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Purchase($row["idPurchase"], $row["content"], $row["netWeight"], $row["longValue"], $row["width"], $row["high"], $row["idCustomer"], $row["trackingNumber"], $row["idStore"], $row["quantity"], $row["idShipping"], $row["createdTimestamp"], $row["requested"]));
        }
        
        return $objects;
    }
    
    public static function getRecordsFiltered($search) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection,
            "SELECT
                COUNT(*)
            FROM
                Purchase P
                INNER JOIN Customer C ON C.idCustomer = P.idCustomer
            WHERE
                P.trackingNumber LIKE CONCAT('%',?,'%')
                OR P.content LIKE CONCAT('%',?,'%')
                OR P.createdTimestamp LIKE CONCAT('%',?,'%')
                OR C.lockerNumber LIKE CONCAT('%',?,'%')
                OR C.names LIKE CONCAT('%',?,'%');",
            array($search, $search, $search, $search, $search));
        while ($row = $query->fetch_assoc()) {
            return $row["COUNT(*)"];
        }
    }
    
    public static function getRecordsTotal() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT COUNT(*) FROM Purchase;");
        while ($row = $query->fetch_assoc()) {
            return $row["COUNT(*)"];
        }
    }
    
}