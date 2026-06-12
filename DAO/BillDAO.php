<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class BillDAO {
    
    public static function getBillById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Bill WHERE idBill = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Bill($row["idBill"], $row["billNumber"], $row["idCustomer"], $row["from"], $row["fromAddress"], $row["fromPhone"], $row["to"], $row["toAddress"], $row["toPhone"], $row["toCountry"], $row["currency"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"]);
        }
        
        return null;
    }
    
    public static function getBillByNumber($number) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Bill WHERE billNumber = ?;", array($number));
        while ($row = $query->fetch_assoc()) {
            return new Bill($row["idBill"], $row["billNumber"], $row["idCustomer"], $row["from"], $row["fromAddress"], $row["fromPhone"], $row["to"], $row["toAddress"], $row["toPhone"], $row["toCountry"], $row["currency"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"]);
        }
        
        return null;
    }
    
    public static function getBills() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Bill ORDER BY createdTimestamp DESC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Bill($row["idBill"], $row["billNumber"], $row["idCustomer"], $row["from"], $row["fromAddress"], $row["fromPhone"], $row["to"], $row["toAddress"], $row["toPhone"], $row["toCountry"], $row["currency"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function getBillsDataTables($start, $length, $search, $company, $country, $order) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "CALL PA_Bill_DataTables(?,?,?,?,?,?);", array($start, $length, $search,$company,$country,$order));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Bill($row["idBill"], $row["billNumber"], $row["idCustomer"], $row["from"], $row["fromAddress"], $row["fromPhone"], $row["to"], $row["toAddress"], $row["toPhone"], $row["toCountry"], $row["currency"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function getRecordsTotal() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT COUNT(*) FROM Bill;");
        while ($row = $query->fetch_assoc()) {
            return $row["COUNT(*)"];
        }
    }
    
    public static function getRecordsFiltered($search,$company,$country) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "CALL PA_Bill_DataTables_Filtred(?,?,?)", array($search, $company, $country));
        while ($row = $query->fetch_assoc()) {
            return $row["COUNT(*)"];
        }
    }
    
    public static function getBillsNotAvoid() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Bill WHERE annulled = 0 ORDER BY createdTimestamp DESC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Bill($row["idBill"], $row["billNumber"], $row["idCustomer"], $row["from"], $row["fromAddress"], $row["fromPhone"], $row["to"], $row["toAddress"], $row["toPhone"], $row["toCountry"], $row["currency"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function getTracking($startDate,$endDate,$country,$company) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "CALL PA_Bill_GetTracking(?,?,?,?);",array($startDate,$endDate,$country,$company));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Bill($row["idBill"], $row["billNumber"], $row["idCustomer"], $row["from"], $row["fromAddress"], $row["fromPhone"], $row["to"], $row["toAddress"], $row["toPhone"], $row["toCountry"], $row["currency"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function create($date, $idCustomer, $from, $fromAddress, $fromPhone, $to, $toAddress, $toPhone, $toCountry, $currency, $idShipmentCompany) {
        $id = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "INSERT INTO Bill (idBill, idCustomer, `from`, fromAddress, fromPhone, `to`, toAddress, toPhone, toCountry, currency, idShipmentCompany, createdTimestamp) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);", array($id, $idCustomer, $from, $fromAddress, $fromPhone, $to, $toAddress, $toPhone, $toCountry, $currency, $idShipmentCompany, $date));
        return $id;
    }
    
    public static function edit($date, $idBill, $idCustomer, $from, $fromAddress, $fromPhone, $to, $toAddress, $toPhone, $toCountry, $currency, $idShipmentCompany) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "UPDATE Bill SET createdTimestamp=?, idCustomer=?, `from`=?, fromAddress=?, fromPhone=?, `to`=?, toAddress=?, toPhone=?, toCountry=?, currency=?, idShipmentCompany=? WHERE idBill=?;", array($date, $idCustomer, $from, $fromAddress, $fromPhone, $to, $toAddress, $toPhone, $toCountry, $currency, $idShipmentCompany, $idBill));
    }
    
    public static function annull($id) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "UPDATE Bill SET annulled = 1 WHERE idBill=?;", array($id));
    }
    
    public static function getBillsByIdCustomer($idCustomer) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Bill WHERE idCustomer = ? AND annulled = 0 ORDER BY createdTimestamp DESC;", array($idCustomer));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Bill($row["idBill"], $row["billNumber"], $row["idCustomer"], $row["from"], $row["fromAddress"], $row["fromPhone"], $row["to"], $row["toAddress"], $row["toPhone"], $row["toCountry"], $row["currency"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function getBillsByCurrency($currency) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Bill WHERE currency = ? AND annulled = 0;", array($currency));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Bill($row["idBill"], $row["billNumber"], $row["idCustomer"], $row["from"], $row["fromAddress"], $row["fromPhone"], $row["to"], $row["toAddress"], $row["toPhone"], $row["toCountry"], $row["currency"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    public static function getBillsByCountryNoCustomer($country) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Bill WHERE toCountry = ? AND annulled = 0 AND idCustomer IS NULL;", array($country));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Bill($row["idBill"], $row["billNumber"], $row["idCustomer"], $row["from"], $row["fromAddress"], $row["fromPhone"], $row["to"], $row["toAddress"], $row["toPhone"], $row["toCountry"], $row["currency"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"]));
        }
        
        return $objects;
    }
    
    
    
}
