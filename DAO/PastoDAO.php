<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class PastoDAO {

    public static function create($date,$TRM,$detail,$realCostPurchaseUSD,$freightSaleUSD,$salePriceCOP,$freightUSD,$responsible) {
        $UUID = Helpers::UUID();
        $connection = new Connection();
        
        $totalCostUSD = $realCostPurchaseUSD + $freightSaleUSD;
        $totalCostCOP = $totalCostUSD * $TRM;
        $utilityCOP = $salePriceCOP - $totalCostCOP;
        
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO Pasto (idPasto,date,detail,realCostPurchaseUSD,freightSaleUSD,totalCostUSD,totalCostCOP,salePriceCOP,utilityCOP,freightUSD,responsible) VALUES (?,?,?,?,?,?,?,?,?,?,?);",
                array($UUID,$date,$detail,$realCostPurchaseUSD,$freightSaleUSD,$totalCostUSD,$totalCostCOP,$salePriceCOP,$utilityCOP,$freightUSD,$responsible));
        return $UUID;
    }

    public static function get() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Pasto ORDER BY date ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Pasto($row["idPasto"],$row["date"],$row["detail"],$row["realCostPurchaseUSD"],$row["freightSaleUSD"],$row["totalCostUSD"],$row["totalCostCOP"],$row["salePriceCOP"],$row["utilityCOP"],$row["freightUSD"],$row["responsible"],$row["status"]));
        }
        
        return $objects;
    }
    
    public static function getById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Pasto WHERE idPasto = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Pasto($row["idPasto"],$row["date"],$row["detail"],$row["realCostPurchaseUSD"],$row["freightSaleUSD"],$row["totalCostUSD"],$row["totalCostCOP"],$row["salePriceCOP"],$row["utilityCOP"],$row["freightUSD"],$row["responsible"],$row["status"]);
        }
        
        return null;
    }
    
}