<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class InventoryDAO {
    
    public static function create($product, $trm, $usdPrice, $copPrice, $internationalShippingPrice, $nationalShippingPrice, $totalCost, $salePrice, $utility) {
        $id = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute(
                $connection,
                "INSERT INTO Inventory (`id`,`product`,`trm`,`usdPrice`,`copPrice`,`internationalShippingPrice`,`nationalShippingPrice`,`totalCost`,`salePrice`,`utility`) VALUES (?,?,?,?,?,?,?,?,?,?)",
                array($id, $product, $trm, $usdPrice, $copPrice, $internationalShippingPrice, $nationalShippingPrice, $totalCost, $salePrice, $utility));
        return $id;
    }
    
    public static function update($id, $product, $trm, $usdPrice, $copPrice, $internationalShippingPrice, $nationalShippingPrice, $totalCost, $salePrice, $utility) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute(
                $connection,
                "UPDATE Inventory SET `product`=?,`trm`=?,`usdPrice`=?,`copPrice`=?,`internationalShippingPrice`=?,`nationalShippingPrice`=?,`totalCost`=?,`salePrice`=?,`utility`=? WHERE `id`=?",
                array($product, $trm, $usdPrice, $copPrice, $internationalShippingPrice, $nationalShippingPrice, $totalCost, $salePrice, $utility, $id));
    }

    public static function restoreToInventory($id) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute(
                $connection,
                "UPDATE Inventory SET `idInvoice`=null WHERE `id`=?",
                array($id));
    }
    
    public static function sell($id, $idInvoice) {
        $inventoryTracking = InventoryTrackingDAO::getByIdInventory($id);
        foreach ($inventoryTracking as $t) {
            try {
                $inventory = InventoryDAO::getById($id);
                InventoryInvoiceTrackingDAO::create(substr($t->createdTimestamp, 0, 10), $inventory->product . ": " . $t->detail, $idInvoice, $t->user);
            } catch (Exception $e) {
                
            }
        }

        $connection = new Connection();
        CloudEngineMySQLQuery::execute(
                $connection,
                "UPDATE Inventory SET idInvoice = ? WHERE id=?;",
                array($idInvoice, $id));
    }
    
    public static function getAll() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Inventory WHERE idInvoice IS NULL ORDER BY invoice DESC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Inventory(
                $row["id"],
                $row["invoice"],
                $row["product"],
                $row["trm"],
                $row["usdPrice"],
                $row["copPrice"],
                $row["internationalShippingPrice"],
                $row["nationalShippingPrice"],
                $row["totalCost"],
                $row["salePrice"],
                $row["utility"],
                $row["idInvoice"],
                $row["createdTimestamp"])
            );
        }
        
        return $objects;
    }
    
    public static function getSold() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Inventory WHERE idInvoice IS NOT NULL ORDER BY invoice DESC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Inventory(
                $row["id"],
                $row["invoice"],
                $row["product"],
                $row["trm"],
                $row["usdPrice"],
                $row["copPrice"],
                $row["internationalShippingPrice"],
                $row["nationalShippingPrice"],
                $row["totalCost"],
                $row["salePrice"],
                $row["utility"],
                $row["idInvoice"],
                $row["createdTimestamp"])
            );
        }
        
        return $objects;
    }
    
    public static function getByIdInvoice($idInvoice) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Inventory WHERE idInvoice = ? ORDER BY invoice DESC;", array($idInvoice));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Inventory(
                $row["id"],
                $row["invoice"],
                $row["product"],
                $row["trm"],
                $row["usdPrice"],
                $row["copPrice"],
                $row["internationalShippingPrice"],
                $row["nationalShippingPrice"],
                $row["totalCost"],
                $row["salePrice"],
                $row["utility"],
                $row["idInvoice"],
                $row["createdTimestamp"])
            );
        }
        
        return $objects;
    }
    
    public static function getById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Inventory WHERE id = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Inventory(
                $row["id"],
                $row["invoice"],
                $row["product"],
                $row["trm"],
                $row["usdPrice"],
                $row["copPrice"],
                $row["internationalShippingPrice"],
                $row["nationalShippingPrice"],
                $row["totalCost"],
                $row["salePrice"],
                $row["utility"],
                $row["idInvoice"],
                $row["createdTimestamp"]
            );
        }
        
        return null;
    }
    
}