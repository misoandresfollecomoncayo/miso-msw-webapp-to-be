<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;
use Cloud\Engine\PHP\HTTP\CloudEngineRequest;
use Cloud\Engine\PHP\Utils\CloudEngineStrings;

class InventoryCustomerDAO {
    
    public static function create($name, $documentNumber, $idCity, $address, $phoneNumber, $email) {
        if (InventoryCustomerDAO::getByDocumentNumber($documentNumber) != null) {
            throw new Exception("Ya existe un cliente con el mismo número de documento.");
        }
        
        $UUID = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO InventoryCustomer (id, name, documentNumber, idCity, address, phoneNumber, email) VALUES (?,?,?,?,?,?,?);",
            array($UUID, trim($name), trim($documentNumber), trim($idCity), trim($address), trim($phoneNumber), trim($email)));
    }
    
    public static function update($id, $name, $documentNumber, $idCity, $address, $phoneNumber, $email) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection,
            "UPDATE InventoryCustomer SET name=?, documentNumber=?, idCity=?, address=?, phoneNumber=?, email=? WHERE id=?;",
            array(trim($name), trim($documentNumber), trim($idCity), trim($address), trim($phoneNumber), trim($email), $id));
    }
    
    public static function getByDocumentNumber($documentNumber) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM InventoryCustomer WHERE documentNumber = ?;", array($documentNumber));
        while ($row = $query->fetch_assoc()) {
            return new InventoryCustomer(
                $row["id"],
                $row["name"],
                $row["documentNumber"],
                $row["idCity"],
                $row["address"],
                $row["phoneNumber"],
                $row["email"]
            );
        }
        
        return null;
    }
    
    public static function getById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM InventoryCustomer WHERE id = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new InventoryCustomer(
                $row["id"],
                $row["name"],
                $row["documentNumber"],
                $row["idCity"],
                $row["address"],
                $row["phoneNumber"],
                $row["email"]
            );
        }
        
        return null;
    }
    
    public static function getCustomersDataTables($start, $length, $search) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM InventoryCustomer WHERE (name LIKE CONCAT('%',?,'%') OR documentNumber LIKE CONCAT('%',?,'%') OR address LIKE CONCAT('%',?,'%') OR phoneNumber LIKE CONCAT('%',?,'%') OR email LIKE CONCAT('%',?,'%')) ORDER BY name ASC LIMIT ?, ?;", array($search, $search, $search, $search, $search, $start, $length));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new InventoryCustomer(
                $row["id"],
                $row["name"],
                $row["documentNumber"],
                $row["idCity"],
                $row["address"],
                $row["phoneNumber"],
                $row["email"]
            ));
        }
        
        return $objects;
    }
    
    public static function getRecordsFiltered($search) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT COUNT(*) FROM InventoryCustomer WHERE (name LIKE CONCAT('%',?,'%') OR documentNumber LIKE CONCAT('%',?,'%') OR address LIKE CONCAT('%',?,'%') OR phoneNumber LIKE CONCAT('%',?,'%') OR email LIKE CONCAT('%',?,'%'));", array($search, $search, $search, $search, $search));
        while ($row = $query->fetch_assoc()) {
            return $row["COUNT(*)"];
        }
    }
    
    public static function getRecordsTotal() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT COUNT(*) FROM InventoryCustomer;");
        while ($row = $query->fetch_assoc()) {
            return $row["COUNT(*)"];
        }
    }
    
}