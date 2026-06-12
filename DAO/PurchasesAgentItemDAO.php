<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class PurchasesAgentItemDAO {

    public static function create($date,$TRM,$detail,$realCostPurchaseUSD,$freightSaleUSD,$salePriceCOP,$freightUSD,$reference) {
        $UUID = Helpers::UUID();
        $connection = new Connection();
        
        $totalCostUSD = $realCostPurchaseUSD + $freightSaleUSD;
        $totalCostCOP = $totalCostUSD * $TRM;
        $utilityCOP = $salePriceCOP - $totalCostCOP;
        
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO PurchasesAgentItem (idPurchasesAgentItem,date,TRM,detail,realCostPurchaseUSD,freightSaleUSD,totalCostUSD,totalCostCOP,salePriceCOP,utilityCOP,freightUSD,reference) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);",
                array($UUID,$date,$TRM,$detail,$realCostPurchaseUSD,$freightSaleUSD,$totalCostUSD,$totalCostCOP,$salePriceCOP,$utilityCOP,$freightUSD,$reference));
        return $UUID;
    }

    public static function edit($id,$date,$TRM,$detail,$realCostPurchaseUSD,$freightSaleUSD,$salePriceCOP,$freightUSD,$reference) {    
        $totalCostUSD = $realCostPurchaseUSD + $freightSaleUSD;
        $totalCostCOP = $totalCostUSD * $TRM;
        $utilityCOP = $salePriceCOP - $totalCostCOP;
        
        CloudEngineMySQLQuery::execute(
            new Connection(),
            "UPDATE PurchasesAgentItem SET date=?,TRM=?,detail=?,realCostPurchaseUSD=?,freightSaleUSD=?,totalCostUSD=?,totalCostCOP=?,salePriceCOP=?,utilityCOP=?,freightUSD=?,reference=? WHERE idPurchasesAgentItem = ?;",
            array($date,$TRM,$detail,$realCostPurchaseUSD,$freightSaleUSD,$totalCostUSD,$totalCostCOP,$salePriceCOP,$utilityCOP,$freightUSD,$reference,$id));
    }
    
    public static function pay($id) {
        CloudEngineMySQLQuery::execute(new Connection(), "UPDATE PurchasesAgentItem SET status = 'PAID' WHERE idPurchasesAgentItem = ?;",array($id));
    }
    
    public static function changeStatus($id,$status) {
        CloudEngineMySQLQuery::execute(new Connection(), "UPDATE PurchasesAgentItem SET status = ? WHERE idPurchasesAgentItem = ?;",array($status,$id));
    }
    
    public static function delete($id) {
        CloudEngineMySQLQuery::execute(new Connection(), "DELETE FROM PurchasesAgentItem WHERE idPurchasesAgentItem = ?;",array($id));
    }
    
    public static function get() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM PurchasesAgentItem ORDER BY date ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new PurchasesAgentItem($row["idPurchasesAgentItem"],$row["date"],$row["TRM"],$row["detail"],$row["realCostPurchaseUSD"],$row["freightSaleUSD"],$row["totalCostUSD"],$row["totalCostCOP"],$row["salePriceCOP"],$row["utilityCOP"],$row["freightUSD"],$row["status"],$row["reference"]));
        }
        
        return $objects;
    }
    
    public static function getById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM PurchasesAgentItem WHERE idPurchasesAgentItem = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new PurchasesAgentItem($row["idPurchasesAgentItem"],$row["date"],$row["TRM"],$row["detail"],$row["realCostPurchaseUSD"],$row["freightSaleUSD"],$row["totalCostUSD"],$row["totalCostCOP"],$row["salePriceCOP"],$row["utilityCOP"],$row["freightUSD"],$row["status"],$row["reference"]);
        }
        
        return null;
    }
    
}