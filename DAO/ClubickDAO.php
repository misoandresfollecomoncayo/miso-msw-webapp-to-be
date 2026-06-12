<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class ClubickDAO {
    
    public static function getAll() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Clubick ORDER BY date DESC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Clubick(
                $row["id"],
                $row["number"],
                $row["date"],
                $row["customer"],
                $row["customerDocument"],
                $row["customerAddress"],
                $row["customerPhone"],
                $row["product"],
                $row["trm"],
                $row["usdPrice"],
                $row["copPrice"],
                $row["UniexpressShippingPrice"],
                $row["totalPrice"],
                $row["salePrice"],
                $row["nationalShippingPrice"],
                $row["totalToPay"],
                $row["status"],
                $row["utility"],
                $row["utilitySantiago"],
                $row["utilityJulian"])
            );
        }
        
        return $objects;
    }
    
    public static function getById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Clubick WHERE id = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Clubick(
                $row["id"],
                $row["number"],
                $row["date"],
                $row["customer"],
                $row["customerDocument"],
                $row["customerAddress"],
                $row["customerPhone"],
                $row["product"],
                $row["trm"],
                $row["usdPrice"],
                $row["copPrice"],
                $row["UniexpressShippingPrice"],
                $row["totalPrice"],
                $row["salePrice"],
                $row["nationalShippingPrice"],
                $row["totalToPay"],
                $row["status"],
                $row["utility"],
                $row["utilitySantiago"],
                $row["utilityJulian"]);
        }
        
        return null;
    }
    
    public static function save($id, $date, $customer, $customerDocument, $customerAddress, $customerPhone, $product, $trm, $usdPrice, $copPrice, $UniexpressShippingPrice, $totalPrice, $salePrice, $nationalShippingPrice, $totalToPay, $status, $utility, $utilitySantiago, $utilityJulian) {
        $exists = self::getById($id);
        
        if ($exists == null) {
            $id = Helpers::UUID();
            $connection = new Connection();
            CloudEngineMySQLQuery::execute($connection, "INSERT INTO Clubick (`id`,`date`,`customer`,`customerDocument`,`customerAddress`,`customerPhone`,`product`,`trm`,`usdPrice`,`copPrice`,`UniexpressShippingPrice`,`totalPrice`,`salePrice`,`nationalShippingPrice`,`totalToPay`,`status`,`utility`,`utilitySantiago`,`utilityJulian`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", array($id, $date, $customer, $customerDocument, $customerAddress, $customerPhone, $product, $trm, $usdPrice, $copPrice, $UniexpressShippingPrice, $totalPrice, $salePrice, $nationalShippingPrice, $totalToPay, $status, $utility, $utilitySantiago, $utilityJulian));
            return $id;
        } else {
            $connection = new Connection();
            CloudEngineMySQLQuery::execute($connection, "UPDATE Clubick SET `date`=?,`customer`=?,`customerDocument`=?,`customerAddress`=?,`customerPhone`=?,`product`=?,`trm`=?,`usdPrice`=?,`copPrice`=?,`UniexpressShippingPrice`=?,`totalPrice`=?,`salePrice`=?,`nationalShippingPrice`=?,`totalToPay`=?,`status`=?,`utility`=?,`utilitySantiago`=?,`utilityJulian`=? WHERE id=?", array($date, $customer, $customerDocument, $customerAddress, $customerPhone, $product, $trm, $usdPrice, $copPrice, $UniexpressShippingPrice, $totalPrice, $salePrice, $nationalShippingPrice, $totalToPay, $status, $utility, $utilitySantiago, $utilityJulian, $id));
            return $id;
        }
    }
    
}