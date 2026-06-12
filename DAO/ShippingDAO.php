<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use \Cloud\Engine\PHP\MySQL\Helpers;

class ShippingDAO {
    
    public static function create($netWeight, $volumetricWeight, $TRM, $poundValue, $volumetricPoundValue, $declaredValue, $tax, $freight, $secure, $additionalValue, $additionalValueDescription, $total, $currency, $paymentMethod, $sequenceNumber, $idShipmentCompany, $date) {
        $UUID = Helpers::UUID();
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "INSERT INTO Shipping (`idShipping`,`status`,`netWeight`,`volumetricWeight`,`poundValue`,`volumetricPoundValue`,`declaredValue`,`tax`,`freight`,`secure`,`additionalValue`,`additionalValueDescription`,`total`,`currency`,`sequenceNumber`,`idShipmentCompany`,`annulled`,`createdTimestamp`,`TRM`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);", array($UUID, 'ENVIADO', $netWeight, $volumetricWeight, $poundValue, $volumetricPoundValue, $declaredValue, $tax, $freight, $secure, $additionalValue, $additionalValueDescription, $total, $currency, $sequenceNumber, $idShipmentCompany, 0, $date, $TRM));
        //$query = CloudEngineMySQLQuery::execute($connection, "INSERT INTO Shipping (`idShipping`,`status`,`netWeight`,`volumetricWeight`,`poundValue`,`volumetricPoundValue`,`declaredValue`,`tax`,`freight`,`secure`,`additionalValue`,`additionalValueDescription`,`total`,`currency`,`sequenceNumber`,`idShipmentCompany`,`createdTimestamp`,`TRM`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);", array($UUID, 'ENVIADO', 1, 1, 1, 1, 1, 1, 1, 1, 1, '', 12, 'USD', 'aaabbbccc', 1, $date, 3887));

        // Insert tracking
        //PurchaseTrackingDAO::create("Mercancía ingresada a bodega.", $idSystemUser, PurchaseTracking::TYPE_PUBLIC, $UUID);
        
        // Insert notification
        //NotificationDAO::create("Nueva compra registrada en bodega: " . $content, $idCustomer);

        //TODO: Send email notification to customer, if notification is enabled
        
        return $UUID;
    }
    
    public static function edit($id, $netWeight, $volumetricWeight, $TRM, $poundValue, $volumetricPoundValue, $declaredValue, $tax, $freight, $secure, $additionalValue, $additionalValueDescription, $total, $currency, $paymentMethod, $sequenceNumber, $idShipmentCompany, $date) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "UPDATE Shipping SET netWeight=?,volumetricWeight=?,poundValue=?,volumetricPoundValue=?,declaredValue=?,tax=?,freight=?,secure=?,additionalValue=?,additionalValueDescription=?,total=?,currency=?,sequenceNumber=?,idShipmentCompany=?,createdTimestamp=?,TRM=? WHERE idShipping = ?;",
            array($netWeight, $volumetricWeight, $poundValue, $volumetricPoundValue, $declaredValue, $tax, $freight, $secure, $additionalValue, $additionalValueDescription, $total, $currency, $sequenceNumber, $idShipmentCompany, $date, $TRM, $id));

        // Insert tracking
        //PurchaseTrackingDAO::create("Mercancía ingresada a bodega.", $idSystemUser, PurchaseTracking::TYPE_PUBLIC, $UUID);
        
        // Insert notification
        //NotificationDAO::create("Nueva compra registrada en bodega: " . $content, $idCustomer);

        //TODO: Send email notification to customer, if notification is enabled
    }
    
    public static function getTracking($startDate,$endDate,$country,$company) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "CALL PA_Shipment_GetTracking(?,?,?,?)",array($startDate,$endDate,$country,$company));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Shipping($row["idShipping"], $row["shippingNumber"], $row["status"], $row["netWeight"], $row["volumetricWeight"], $row["poundValue"], $row["volumetricPoundValue"], $row["declaredValue"], $row["tax"], $row["freight"], $row["secure"], $row["additionalValue"], $row["additionalValueDescription"], $row["total"], $row["currency"], $row["sequenceNumber"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"], $row["delivered"], $row["TRM"]));
        }
        
        return $objects;
    }
    
    public static function getShippingById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Shipping WHERE idShipping = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new Shipping($row["idShipping"], $row["shippingNumber"], $row["status"], $row["netWeight"], $row["volumetricWeight"], $row["poundValue"], $row["volumetricPoundValue"], $row["declaredValue"], $row["tax"], $row["freight"], $row["secure"], $row["additionalValue"], $row["additionalValueDescription"], $row["total"], $row["currency"], $row["sequenceNumber"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"], $row["delivered"], $row["TRM"]);
        }
        
        return null;
    }
    
    public static function getShippingByNumber($number) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Shipping WHERE shippingNumber = ?;", array($number));
        while ($row = $query->fetch_assoc()) {
            return new Shipping($row["idShipping"], $row["shippingNumber"], $row["status"], $row["netWeight"], $row["volumetricWeight"], $row["poundValue"], $row["volumetricPoundValue"], $row["declaredValue"], $row["tax"], $row["freight"], $row["secure"], $row["additionalValue"], $row["additionalValueDescription"], $row["total"], $row["currency"], $row["sequenceNumber"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"], $row["delivered"], $row["TRM"]);
        }
        
        return null;
    }
    
    public static function getShippings() {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Shipping ORDER BY createdTimestamp DESC, shippingNumber DESC;");
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Shipping($row["idShipping"], $row["shippingNumber"], $row["status"], $row["netWeight"], $row["volumetricWeight"], $row["poundValue"], $row["volumetricPoundValue"], $row["declaredValue"], $row["tax"], $row["freight"], $row["secure"], $row["additionalValue"], $row["additionalValueDescription"], $row["total"], $row["currency"], $row["sequenceNumber"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"], $row["delivered"], $row["TRM"]));
        }
        
        return $objects;
    }
    
    public static function getShipmentsDataTables($start, $length, $search) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "CALL PA_Shimpment_DataTables(?,?,?);", array($start, $length, $search));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Shipping($row["idShipping"], $row["shippingNumber"], $row["status"], $row["netWeight"], $row["volumetricWeight"], $row["poundValue"], $row["volumetricPoundValue"], $row["declaredValue"], $row["tax"], $row["freight"], $row["secure"], $row["additionalValue"], $row["additionalValueDescription"], $row["total"], $row["currency"], $row["sequenceNumber"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"], $row["delivered"], $row["TRM"]));
        }
        
        return $objects;
    }
    
    public static function getRecordsFiltered($search) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "CALL PA_Shipment_DataTables_Filtred(?)", array($search));
        while ($row = $query->fetch_assoc()) {
            return $row["COUNT(*)"];
        }
    }
    
    public static function getRecordsTotal() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT COUNT(*) FROM Shipping;");
        while ($row = $query->fetch_assoc()) {
            return $row["COUNT(*)"];
        }
    }
    
    public static function getShippingsByCustomer($customer) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection,
            "SELECT
                S.idShipping,
                S.shippingNumber,
                S.status,
                S.netWeight,
                S.volumetricWeight,
                S.poundValue,
                S.volumetricPoundValue,
                S.declaredValue,
                S.tax,
                S.freight,
                S.secure,
                S.additionalValue,
                S.additionalValueDescription,
                S.total,
                S.currency,
                S.sequenceNumber,
                S.idShipmentCompany,
                S.annulled,
                S.createdTimestamp,
                S.delivered,
                S.TRM
            FROM
                Shipping S
                INNER JOIN Purchase P ON P.idShipping = S.idShipping
            WHERE
                P.idCustomer = ? AND
                annulled = 0
            GROUP BY
                S.idShipping,
                S.shippingNumber,
                S.status,
                S.netWeight,
                S.volumetricWeight,
                S.poundValue,
                S.volumetricPoundValue,
                S.declaredValue,
                S.tax,
                S.freight,
                S.secure,
                S.additionalValue,
                S.additionalValueDescription,
                S.total,
                S.sequenceNumber,
                S.createdTimestamp
            ORDER BY
                S.shippingNumber DESC;", array($customer->getIdCustomer()));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Shipping($row["idShipping"], $row["shippingNumber"], $row["status"], $row["netWeight"], $row["volumetricWeight"], $row["poundValue"], $row["volumetricPoundValue"], $row["declaredValue"], $row["tax"], $row["freight"], $row["secure"], $row["additionalValue"], $row["additionalValueDescription"], $row["total"], $row["currency"], $row["sequenceNumber"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"], $row["delivered"], $row["TRM"]));
        }
        
        return $objects;
    }
    
    public static function annull($id) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute($connection, "UPDATE Shipping SET annulled = 1 WHERE idShipping=?;", array($id));
        
        $connection2 = new Connection();
        CloudEngineMySQLQuery::execute($connection2, "UPDATE Purchase SET idShipping = null WHERE idShipping=?;", array($id));
    }
    
    public static function getShipmentsByCurrency($currency) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM Shipping WHERE currency = ?;", array($currency));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new Shipping($row["idShipping"], $row["shippingNumber"], $row["status"], $row["netWeight"], $row["volumetricWeight"], $row["poundValue"], $row["volumetricPoundValue"], $row["declaredValue"], $row["tax"], $row["freight"], $row["secure"], $row["additionalValue"], $row["additionalValueDescription"], $row["total"], $row["currency"], $row["sequenceNumber"], $row["idShipmentCompany"], $row["annulled"], $row["createdTimestamp"], $row["delivered"], $row["TRM"]));
        }
        
        return $objects;
    }
    
    public static function deliver($id) {
        CloudEngineMySQLQuery::execute(new Connection(), "UPDATE Shipping SET delivered = 1 WHERE idShipping=?", array($id));
    }
    
}