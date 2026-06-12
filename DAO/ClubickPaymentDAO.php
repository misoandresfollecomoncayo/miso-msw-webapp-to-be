<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class ClubickPaymentDAO {
    
    public static function add($date, $amount, $method, $user, $idClubick) {
        $id = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO ClubickPayment (`id`,`date`,`amount`,`method`,`user`,`idClubick`) VALUES (?,?,?,?,?,?)", array($id, $date, $amount, $method, $user, $idClubick));
        return $id;
    }
    
    public static function getAllByIdClubick($idClubick) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM ClubickPayment WHERE idClubick = ? ORDER BY date DESC;", array($idClubick));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new ClubickPayment(
                $row["id"],
                $row["date"],
                $row["amount"],
                $row["method"],
                $row["user"],
                $row["idClubick"])
            );
        }
        
        return $objects;
    }
    
}
