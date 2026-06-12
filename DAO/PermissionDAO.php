<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class PermissionDAO {
    
    public static function getPermissionsByRole($role) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT P.* FROM Permission P INNER JOIN Role_Permission RP ON P.idPermission = RP.idPermission WHERE RP.idRole = ? ORDER BY P.order ASC;", array($role->getIdRole()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Permission($row["idPermission"],$row["name"],$row["nameEnglish"],$row["file"],$row["icon"],$row["order"]));
        }
        
        return $objects;
    }
    
}
