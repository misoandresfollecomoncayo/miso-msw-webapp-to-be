<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class GiveawayDAO {
    
    public static function create($name, $email, $city, $phone) {
        if (GiveawayDAO::getGiveawayByEmail($email) !== null) {
            throw new Exception("Ya estás participando con el mismo correo electrónico.");
        }
        
        if (GiveawayDAO::getGiveawayByPhone($phone) !== null) {
            throw new Exception("Ya estás participando con el mismo número de celular.");
        }
        
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO Giveaway (name,email,city,phone) VALUES (?,?,?,?);", array($name,$email,$city,$phone));
    }
    
    public static function getGiveawayByEmail($email) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Giveaway WHERE email = ? LIMIT 1;", array($email));
        while ($row = $query->fetch_assoc()) {
            return new Giveaway($row["id"],$row["name"],$row["email"],$row["city"],$row["phone"],$row["createdTimestamp"]);
        }
        
        return null;
    }
    
    public static function getGiveawayByPhone($phone) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Giveaway WHERE phone = ? LIMIT 1;", array($phone));
        while ($row = $query->fetch_assoc()) {
            return new Giveaway($row["id"],$row["name"],$row["email"],$row["city"],$row["phone"],$row["createdTimestamp"]);
        }
        
        return null;
    }
    
}
