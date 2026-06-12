<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class AccessDAO {
    
    public static function getAccessByEmail($email) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM VW_Access WHERE email = ?;", array($email));
        while ($row = $query->fetch_assoc()) {
            return new Access($row["idRegister"], $row["email"], $row["password"], $row["active"], $row["deleted"], $row["type"]);
        }
        
        return null;
    }
    
}
