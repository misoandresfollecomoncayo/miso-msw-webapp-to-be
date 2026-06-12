<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class DocumentTypeDAO {
    
    public static function getDocumentTypeById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM DocumentType WHERE idDocumentType = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new DocumentType($row["idDocumentType"], $row["name"]);
        }
        
        return null;
    }
    
    public static function getDocumentTypes() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM DocumentType ORDER BY name ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new DocumentType($row["idDocumentType"], $row["name"]));
        }
        
        return $objects;
    }
    
}