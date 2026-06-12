<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class RoleDAO {
    
    public static function getRoleById($id, $throwException = false) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Role WHERE idRole = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Role($row["idRole"],$row["name"]);
        }
        
        if (true == $throwException) {
            throw new Exception("El rol buscado no existe.");
        } else {
            return null;
        }
    }
    
}
