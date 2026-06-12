<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class PurchasesAgentDAO {

    public static function create($date,$TRM,$detail,$realCostPurchaseUSD,$freightSaleUSD,$salePriceCOP,$freightUSD,$reference) {
        $UUID = Helpers::UUID();
        $connection = new Connection();
        
        $totalCostUSD = $realCostPurchaseUSD + $freightSaleUSD;
        $totalCostCOP = $totalCostUSD * $TRM;
        $utilityCOP = $salePriceCOP - $totalCostCOP;
        
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO PurchasesAgent (idPurchaseAgent,date,TRM,detail,realCostPurchaseUSD,freightSaleUSD,totalCostUSD,totalCostCOP,salePriceCOP,utilityCOP,freightUSD,reference) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);",
                array($UUID,$date,$TRM,$detail,$realCostPurchaseUSD,$freightSaleUSD,$totalCostUSD,$totalCostCOP,$salePriceCOP,$utilityCOP,$freightUSD,$reference));
        return $UUID;
    }

    public static function get() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM PurchasesAgent ORDER BY date ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new PurchaseAgent($row["idPurchaseAgent"],$row["date"],$row["TRM"],$row["detail"],$row["realCostPurchaseUSD"],$row["freightSaleUSD"],$row["totalCostUSD"],$row["totalCostCOP"],$row["salePriceCOP"],$row["utilityCOP"],$row["freightUSD"],$row["status"],$row["reference"]));
        }
        
        return $objects;
    }
    
    public static function getById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM PurchasesAgent WHERE idPasto = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new PurchaseAgent($row["idPurchaseAgent"],$row["date"],$row["TRM"],$row["detail"],$row["realCostPurchaseUSD"],$row["freightSaleUSD"],$row["totalCostUSD"],$row["totalCostCOP"],$row["salePriceCOP"],$row["utilityCOP"],$row["freightUSD"],$row["status"],$row["reference"]);
        }
        
        return null;
    }
    
}