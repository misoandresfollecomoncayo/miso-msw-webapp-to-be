<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;
use Cloud\Engine\PHP\HTTP\CloudEngineRequest;
use Cloud\Engine\PHP\Utils\CloudEngineStrings;

class CustomerDAO {
    
    public static function create($names, $gender, $birthdate, $language, $idDocumentType, $documentNumber, $idCity, $address, $telephone, $telephone2, $email, $password) {
        if (AccessDAO::getAccessByEmail($email) == null) {
            $UUID = Helpers::UUID();
            $connection = new Connection();
            $query = CloudEngineMySQLQuery::execute($connection, "INSERT INTO Customer (idCustomer, names, gender, birthdate, idDocumentType, documentNumber, idCity, address, telephone, telephone2, email, password, language) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);", array($UUID, trim($names), trim($gender), trim($birthdate), trim($idDocumentType), trim($documentNumber), trim($idCity), trim($address), trim($telephone), trim($telephone2), trim($email), md5($password), $language));
            
            // Insert notification
            if ($language == Customer::LANGUAGE_SPANISH) {
                NotificationDAO::create("Casillero creado.", $UUID);
            } else {
                NotificationDAO::create("P.O. Box created.", $UUID);
            }
            
            // Create activation token
            $token = TokenDAO::create($UUID, Token::TYPE_CUSTOMER);
            
            // Send welcome email
            EmailEngine::customerWelcome($UUID, $token);
            
            return $UUID;
        } else {
            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                throw new Exception("Ya existe un cliente con el mismo correo electrónico.");
            } else {
                throw new Exception("There is already a customer with the same email.");
            }
        }
    }
    
    public static function createFromAdministrator($names, $gender, $birthdate, $language, $idDocumentType, $documentNumber, $idCity, $address, $telephone, $telephone2, $email, $password, $notify) {
        if (AccessDAO::getAccessByEmail($email) == null) {
            $UUID = Helpers::UUID();
            $connection = new Connection();
            $query = CloudEngineMySQLQuery::execute($connection, "INSERT INTO Customer (idCustomer, names, gender, birthdate, idDocumentType, documentNumber, idCity, address, telephone, telephone2, email, password, active, language) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,1,?);", array($UUID, trim($names), trim($gender), trim($birthdate), trim($idDocumentType), trim($documentNumber), trim($idCity), trim($address), trim($telephone), trim($telephone2), trim($email), md5($password), $language));
            
            // Insert notification
            if ($language == Customer::LANGUAGE_SPANISH) {
                NotificationDAO::create("Casillero creado desde administrador.", $UUID); 
            } else {
                NotificationDAO::create("Locker created from administrator.", $UUID); 
            }
            
            // Send welcome email
            if (CloudEngineStrings::stringToBool($notify) == true) {
                EmailEngine::customerFromAdministratorWelcome($UUID, $password);
            }
            
            return $UUID;
        } else {
            throw new Exception("Ya existe un cliente con el mismo correo electrónico.");
        }
    }
    
    public static function update($idCustomer, $names, $gender, $birthdate, $language, $idDocumentType, $documentNumber, $idCity, $address, $telephone, $telephone2, $email) {
        $accessSameEmail = AccessDAO::getAccessByEmail($email);
        
        if ($accessSameEmail == null ||
            $accessSameEmail->getIdRegister() == $idCustomer) {
            $connection = new Connection();
            $query = CloudEngineMySQLQuery::execute($connection, "UPDATE Customer SET names=?, gender=?, birthdate=?, idDocumentType=?, documentNumber=?, idCity=?, address=?, telephone=?, telephone2=?, email=?, language=? WHERE idCustomer=?;", array($names, $gender, $birthdate, $idDocumentType, $documentNumber, $idCity, $address, $telephone, $telephone2, $email, $language, $idCustomer));
        } else {
            throw new Exception("Ya existe un cliente con el mismo correo electrónico");
        }
    }
    
    public static function updateSession($idCustomer, $language, $idCity, $address, $telephone, $telephone2, $email) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE Customer SET idCity=?,address=?,telephone=?,telephone2=?,email=?,language=? WHERE idCustomer=?;", array($idCity, $address, $telephone, $telephone2, $email, $language, $idCustomer));
    }
    
    public static function delete($id) {
        $customer = CustomerDAO::getCustomerById($id);
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE Customer SET deleted = 1 WHERE idCustomer = ?;", array($id));
        
        // Insert notification
        if ($customer->getLanguage() == Customer::LANGUAGE_SPANISH) {
            NotificationDAO::create("Cuenta eliminada.", $id);
        } else {
            NotificationDAO::create("Account deleted.", $id);
        }
    }
    
    public static function active($id) {
        $customer = CustomerDAO::getCustomerById($id);
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE Customer SET active = 1 WHERE idCustomer = ?;", array($id));
        
        // Insert notification
        if ($customer->getLanguage() == Customer::LANGUAGE_SPANISH) {
            NotificationDAO::create("Cuenta activada.", $id);
        } else {
            NotificationDAO::create("Account activated.", $id);
        }
    }
    
    public static function inactive($id) {
        $customer = CustomerDAO::getCustomerById($id);
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE Customer SET active = 0 WHERE idCustomer = ?;", array($id));
        
        // Insert notification
        if ($customer->getLanguage() == Customer::LANGUAGE_SPANISH) {
            NotificationDAO::create("Cuenta desactivada.", $id);
        } else {
            NotificationDAO::create("Account deactivated.", $id);
        }
    }
    
    public static function getCustomerById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Customer WHERE idCustomer = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Customer($row["idCustomer"],$row["lockerNumber"],$row["names"],$row["gender"],$row["birthdate"],$row["idDocumentType"],$row["documentNumber"],$row["idCity"],$row["address"],$row["telephone"],$row["telephone2"],$row["email"],$row["password"],$row["active"],$row["deleted"],$row["createdTimestamp"],$row["idRole"],$row["language"]);
        }
        
        return null;
    }
    
    public static function getCustomerByLocker($locker) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Customer WHERE active = 1 AND deleted = 0 AND lockerNumber = ?;", array($locker));
        while ($row = $query->fetch_assoc()) {
            return new Customer($row["idCustomer"],$row["lockerNumber"],$row["names"],$row["gender"],$row["birthdate"],$row["idDocumentType"],$row["documentNumber"],$row["idCity"],$row["address"],$row["telephone"],$row["telephone2"],$row["email"],$row["password"],$row["active"],$row["deleted"],$row["createdTimestamp"],$row["idRole"],$row["language"]);
        }
        
        return null;
    }
    
    public static function getCustomerByDocument($document) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Customer WHERE active = 1 AND deleted = 0 AND documentNumber = ?;", array($document));
        while ($row = $query->fetch_assoc()) {
            return new Customer($row["idCustomer"],$row["lockerNumber"],$row["names"],$row["gender"],$row["birthdate"],$row["idDocumentType"],$row["documentNumber"],$row["idCity"],$row["address"],$row["telephone"],$row["telephone2"],$row["email"],$row["password"],$row["active"],$row["deleted"],$row["createdTimestamp"],$row["idRole"],$row["language"]);
        }
        
        return null;
    }
    
    public static function getCustomers() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Customer WHERE deleted = 0 ORDER BY names ASC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Customer($row["idCustomer"],$row["lockerNumber"],$row["names"],$row["gender"],$row["birthdate"],$row["idDocumentType"],$row["documentNumber"],$row["idCity"],$row["address"],$row["telephone"],$row["telephone2"],$row["email"],$row["password"],$row["active"],$row["deleted"],$row["createdTimestamp"],$row["idRole"],$row["language"]));
        }
        
        return $objects;
    }
    
    public static function getCustomersByIdCountry($idCountry) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT C.* FROM Customer C INNER JOIN City Ci ON Ci.idCity = C.idCity INNER JOIN Country Co ON Co.idCountry = Ci.idCountry WHERE C.deleted = 0 AND Co.idCountry = ?;", array($idCountry));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Customer($row["idCustomer"],$row["lockerNumber"],$row["names"],$row["gender"],$row["birthdate"],$row["idDocumentType"],$row["documentNumber"],$row["idCity"],$row["address"],$row["telephone"],$row["telephone2"],$row["email"],$row["password"],$row["active"],$row["deleted"],$row["createdTimestamp"],$row["idRole"],$row["language"]));
        }
        
        return $objects;
    }
    
    public static function getCustomersByLockerOrNames($search) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Customer WHERE deleted = 0 AND active = 1 AND (lockerNumber = ? OR names LIKE CONCAT('%',?,'%'));", array($search, $search));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Customer($row["idCustomer"],$row["lockerNumber"],$row["names"],$row["gender"],$row["birthdate"],$row["idDocumentType"],$row["documentNumber"],$row["idCity"],$row["address"],$row["telephone"],$row["telephone2"],$row["email"],$row["password"],$row["active"],$row["deleted"],$row["createdTimestamp"],$row["idRole"],$row["language"]));
        }
        
        return $objects;
    }
    
    public static function getCustomersDataTables($start, $length, $search) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Customer WHERE deleted = 0 AND (lockerNumber LIKE CONCAT('%',?,'%') OR names LIKE CONCAT('%',?,'%') OR email LIKE CONCAT('%',?,'%')) ORDER BY names ASC LIMIT ?, ?;", array($search, $search, $search, $start, $length));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Customer($row["idCustomer"],$row["lockerNumber"],$row["names"],$row["gender"],$row["birthdate"],$row["idDocumentType"],$row["documentNumber"],$row["idCity"],$row["address"],$row["telephone"],$row["telephone2"],$row["email"],$row["password"],$row["active"],$row["deleted"],$row["createdTimestamp"],$row["idRole"],$row["language"]));
        }
        
        return $objects;
    }
    
    public static function getRecordsFiltered($search) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT COUNT(*) FROM Customer WHERE deleted = 0 AND (lockerNumber LIKE CONCAT('%',?,'%') OR names LIKE CONCAT('%',?,'%') OR email LIKE CONCAT('%',?,'%'));", array($search, $search, $search));
        while ($row = $query->fetch_assoc()) {
            return $row["COUNT(*)"];
        }
    }
    
    public static function getRecordsTotal() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT COUNT(*) FROM Customer WHERE deleted = 0;");
        while ($row = $query->fetch_assoc()) {
            return $row["COUNT(*)"];
        }
    }
    
    public static function updatePassword($id, $new) {
        $customer = CustomerDAO::getCustomerById($id);
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE Customer SET password = ? WHERE idCustomer = ?;", array(md5($new), $id));
        
        // Insert notification
        if ($customer->getLanguage() == Customer::LANGUAGE_SPANISH) {
            NotificationDAO::create("Clave actualizada.", $id);
        } else {
            NotificationDAO::create("Password updated.", $id);
        }
    }
    
    public static function updateLanguage($id, $language) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "UPDATE Customer SET language = ? WHERE idCustomer = ?;", array($language, $id));
    }
    
    //
    
    public static function getCustomersPendingShipmentsDataTables($start, $length, $search) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT C.* FROM Customer C INNER JOIN Purchase P ON P.idCustomer = C.idCustomer WHERE C.deleted = 0 AND P.idShipping IS NULL AND (C.lockerNumber LIKE CONCAT('%',?,'%') OR C.names LIKE CONCAT('%',?,'%')) GROUP BY C.lockerNumber ORDER BY C.names ASC LIMIT ?, ?;", array($search, $search, $start, $length));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Customer($row["idCustomer"],$row["lockerNumber"],$row["names"],$row["gender"],$row["birthdate"],$row["idDocumentType"],$row["documentNumber"],$row["idCity"],$row["address"],$row["telephone"],$row["telephone2"],$row["email"],$row["password"],$row["active"],$row["deleted"],$row["createdTimestamp"],$row["idRole"],$row["language"]));
        }
        
        return $objects;
    }
    
}
