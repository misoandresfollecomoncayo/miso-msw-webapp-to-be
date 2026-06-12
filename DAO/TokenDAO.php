<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\Utils\CloudEngineStrings;

class TokenDAO {
    
    public static function create($idUser, $type) {
        $id = CloudEngineStrings::randomString(64);
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "INSERT INTO Token (idToken,idUser,type) VALUES (?,?,?);", array($id, $idUser, $type));    
        return $id;
    }
    
    public static function getTokenById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Token WHERE idToken = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Token($row["idToken"], $row["idUser"], $row["type"], $row["used"]);
        }
        
        return null;
    }
    
    public static function consume($token) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE Token SET used = 1 WHERE idToken = ?;", array($token->getIdToken()));
    }
    
}
