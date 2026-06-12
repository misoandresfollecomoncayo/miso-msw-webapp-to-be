<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\Helpers;

class InventoryInvoiceDAO {
    
    public static function create($sellingCompany, $idInventoryCustomer) {
        
        $invoiceNumber = 1000 + InventoryInvoiceDAO::getRecordsNumberBySellingCompany($sellingCompany);
        
        $id = Helpers::UUID();
        $connection = new Connection();
        CloudEngineMySQLQuery::execute(
                $connection,
                "INSERT INTO InventoryInvoice (`id`,`invoiceNumber`,`sellingCompany`,`idInventoryCustomer`) VALUES (?,?,?,?)",
                array($id, $invoiceNumber, $sellingCompany, $idInventoryCustomer));
        return $id;
    }
    
    public static function getRecordsNumberBySellingCompany($sellingCompany) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT COUNT(*) FROM InventoryInvoice WHERE sellingCompany = ?;", array($sellingCompany));
        while ($row = $query->fetch_assoc()) {
            return $row["COUNT(*)"];
        }
    }
    
    public static function getTable($sellingCompany) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM VW_TableInventorySales WHERE sellingCompany = ?;", array($sellingCompany));
        while ($row = $query->fetch_assoc()) {
            $obj = new stdClass();
            $obj->invoiceId = $row["invoiceId"];
            $obj->itemId = $row["itemId"];
            $obj->invoiceCreatedTimestamp = $row["invoiceCreatedTimestamp"];
            $obj->invoiceNumber = $row["invoiceNumber"];
            $obj->customer = $row["customer"];
            $obj->product = $row["product"];
            $obj->trm = $row["trm"];
            $obj->usdPrice = $row["usdPrice"];
            $obj->copPrice = $row["copPrice"];
            $obj->internationalShippingPrice = $row["internationalShippingPrice"];
            $obj->nationalShippingPrice = $row["nationalShippingPrice"];
            $obj->totalCost = $row["totalCost"];
            $obj->salePrice = $row["salePrice"];
            $obj->utility = $row["utility"];
            $obj->paid = $row["paid"];
            $obj->pending = $row["pending"];
            $obj->status = $row["status"];
            $obj->lastTracking = $row["lastTracking"] != null ? $row["lastTracking"] : "";
            $obj->completeTracking = $row["completeTracking"] != null ? $row["completeTracking"] : "";
            array_push($objects, $obj);
        }
        
        return $objects;
    }

    /*public static function getBySellingCompany($sellingCompany) {
        $objects = array();
        
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM InventoryInvoice WHERE sellingCompany = ? ORDER BY createdTimestamp DESC;", array($sellingCompany));
        while ($row = $query->fetch_assoc()) {
            array_push($objects, new InventoryInvoice(
                $row["id"],
                $row["invoiceNumber"],
                $row["sellingCompany"],
                $row["idInventoryCustomer"],
                $row["createdTimestamp"],
                $row["annulled"])
            );
        }
        
        return $objects;
    }*/

    public static function getBySellingCompany($sellingCompany, $page, $search="") {
        $objects = array();
        
        $connection = new Connection();
        $query = null;
        if ($search == "") {
            $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM VW_TableInventorySales WHERE sellingCompany = ? LIMIT 50 OFFSET ?;", array($sellingCompany, ($page-1)*50));
        } else {
            $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM VW_TableInventorySales WHERE sellingCompany = ? AND (invoiceCreatedTimestamp like concat('%',?,'%') OR invoiceNumber like concat('%',?,'%') OR customer like concat('%',?,'%') OR product like concat('%',?,'%') OR status like concat('%',?,'%') OR completeTracking like concat('%',?,'%'));", array($sellingCompany, $search, $search, $search, $search, $search, $search));
        }
        while ($row = $query->fetch_assoc()) {
            $obj = new stdClass();
            $obj->invoiceId = $row["invoiceId"];
            $obj->itemId = $row["itemId"];
            $obj->invoiceCreatedTimestamp = $row["invoiceCreatedTimestamp"];
            $obj->invoiceNumber = $row["invoiceNumber"];
            $obj->customer = $row["customer"];
            $obj->product = $row["product"];
            $obj->trm = $row["trm"];
            $obj->usdPrice = $row["usdPrice"];
            $obj->copPrice = $row["copPrice"];
            $obj->internationalShippingPrice = $row["internationalShippingPrice"];
            $obj->nationalShippingPrice = $row["nationalShippingPrice"];
            $obj->totalCost = $row["totalCost"];
            $obj->salePrice = $row["salePrice"];
            $obj->utility = $row["utility"];
            $obj->paid = $row["paid"];
            $obj->pending = $row["pending"];
            $obj->status = $row["status"];
            $obj->lastTracking = $row["lastTracking"] != null ? $row["lastTracking"] : "";
            $obj->completeTracking = $row["completeTracking"] != null ? $row["completeTracking"] : "";
            array_push($objects, $obj);
        }
        
        return $objects;
    }

    public static function getIndicators($sellingCompany) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "CALL PA_GetInventorySalesIndicators(?)", array($sellingCompany));
        while ($row = $query->fetch_assoc()) {
            $obj = new stdClass();
            $obj->utility = $row["utility"];
            $obj->paid = $row["paid"];
            $obj->pending = $row["pending"];
            return $obj;
        }
    }

    public static function getBySellingCompanyPagesNumber($sellingCompany) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "select count(*) as count from Inventory I inner join InventoryInvoice II on I.idInvoice = II.id where II.sellingCompany = ?;", array($sellingCompany));
        $pages = 0;
        while ($row = $query->fetch_assoc()) {
            $pages = $row["count"];
        }
        return ceil($pages/50);
    }
    
    public static function getBySellingCompanyProductsNumber($sellingCompany) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "select count(*) as count from Inventory I inner join InventoryInvoice II on I.idInvoice = II.id where II.sellingCompany = ?;", array($sellingCompany));
        while ($row = $query->fetch_assoc()) {
            return $row["count"];
        }
    }

    public static function getById($id) {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "SELECT * FROM InventoryInvoice WHERE id = ?;", array($id));
        while ($row = $query->fetch_assoc()) {
            return new InventoryInvoice(
                $row["id"],
                $row["invoiceNumber"],
                $row["sellingCompany"],
                $row["idInventoryCustomer"],
                $row["createdTimestamp"],
                $row["annulled"]
            );
        }
        
        return null;
    }

    public static function void($id) {
        $connection = new Connection();
        CloudEngineMySQLQuery::execute(
                $connection,
                "UPDATE InventoryInvoice SET annulled = 1 WHERE id = ?;",
                array($id));
    }
    
}