<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class PaymentMethodDAO {
    
    public static function getPaymentMethodById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM PaymentMethod WHERE idPaymentMethod = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new PaymentMethod($row["idPaymentMethod"],$row["name"]);
        }
        
        return null;
    }
    
    public static function getPaymentMethods() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM PaymentMethod WHERE disabled = 0 ORDER BY name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new PaymentMethod($row["idPaymentMethod"],$row["name"]));
        }
        
        return $objects;
    }
    
}