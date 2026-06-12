<?php

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;

class DashboardDAO {
    
    public static function getPoundsColombia() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "select sum(t.value) as 'value' from (select sum(I.weight) as 'value' from Bill B inner join BillItem I on I.idBill = B.idBill where B.annulled = 0 and B.toCountry = 'd9c125a8-c7fa-4b88-a7b9-91172845c9d2' and B.idCustomer is null union all select sum(I.weight) as 'value' from Bill B inner join BillItem I on I.idBill = B.idBill inner join Customer C on C.idCustomer = B.idCustomer where B.idCustomer is not null and B.annulled = 0 and C.idCity in (select idCity from City where idCountry = 'd9c125a8-c7fa-4b88-a7b9-91172845c9d2') union all select sum(S.netWeight) as 'value' from Shipping S inner join Purchase P on P.idShipping = S.idShipping inner join Customer C on C.idCustomer = P.idCustomer where S.annulled = 0 and C.idCity in (select idCity from City where idCountry = 'd9c125a8-c7fa-4b88-a7b9-91172845c9d2')) as t;");
        while ($row = $query->fetch_assoc()) {
            return new Dashboard("Libras a Colombia", $row["value"]);
        }
    }
    
    public static function getPoundsEcuador() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "select sum(t.value) as 'value' from (select sum(I.weight) as 'value' from Bill B inner join BillItem I on I.idBill = B.idBill where B.annulled = 0 and B.toCountry = '5f39cf93-938e-41c2-8fef-5515ea83b657' and B.idCustomer is null union all select sum(I.weight) as 'value' from Bill B inner join BillItem I on I.idBill = B.idBill inner join Customer C on C.idCustomer = B.idCustomer where B.idCustomer is not null and B.annulled = 0 and C.idCity in (select idCity from City where idCountry = '5f39cf93-938e-41c2-8fef-5515ea83b657') union all select sum(S.netWeight) as 'value' from Shipping S inner join Purchase P on P.idShipping = S.idShipping inner join Customer C on C.idCustomer = P.idCustomer where S.annulled = 0 and C.idCity in (select idCity from City where idCountry = '5f39cf93-938e-41c2-8fef-5515ea83b657')) as t;");
        while ($row = $query->fetch_assoc()) {
            return new Dashboard("Libras a Ecuador", $row["value"]);
        }
    }
    
    public static function getShipmentsColombia() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "select sum(t.value) as 'value' from (select count(*) as 'value' from Bill where toCountry = 'd9c125a8-c7fa-4b88-a7b9-91172845c9d2' and annulled = 0 and idCustomer is null union all select count(*) as 'value' from Bill B inner join Customer C on B.idCustomer = C.idCustomer where B.idCustomer is not null and B.annulled = 0 and C.idCity in (select idCity from City where idCountry = 'd9c125a8-c7fa-4b88-a7b9-91172845c9d2') union all select count(*) as 'value' from Shipping S inner join Purchase P on P.idShipping = S.idShipping inner join Customer C on C.idCustomer = P.idCustomer where S.annulled = 0 and C.idCity in (select idCity from City where idCountry = 'd9c125a8-c7fa-4b88-a7b9-91172845c9d2')) as t;");
        while ($row = $query->fetch_assoc()) {
            return new Dashboard("Envíos a Colombia", $row["value"]);
        }
    }
    
    public static function getShipmentsEcuador() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "select sum(t.value) as 'value' from (select count(*) as 'value' from Bill where toCountry = '5f39cf93-938e-41c2-8fef-5515ea83b657' and annulled = 0 and idCustomer is null union all select count(*) as 'value' from Bill B inner join Customer C on B.idCustomer = C.idCustomer where B.idCustomer is not null and B.annulled = 0 and C.idCity in (select idCity from City where idCountry = '5f39cf93-938e-41c2-8fef-5515ea83b657') union all select count(*) as 'value' from Shipping S inner join Purchase P on P.idShipping = S.idShipping inner join Customer C on C.idCustomer = P.idCustomer where S.annulled = 0 and C.idCity in (select idCity from City where idCountry = '5f39cf93-938e-41c2-8fef-5515ea83b657')) as t;");
        while ($row = $query->fetch_assoc()) {
            return new Dashboard("Envíos a Ecuador", $row["value"]);
        }
    }
    
    public static function getManualInvoices() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "select count(*) as 'value' from Bill where annulled = 0;");
        while ($row = $query->fetch_assoc()) {
            return new Dashboard("Facturas", $row["value"]);
        }
    }
    
    public static function getTotalCashCOP() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "select sum(t.amount) as 'value' from ((select amount from BillPartialPayment P inner join Bill B on P.idBill = B.idBill where B.annulled = 0 and B.currency = 'COP') union all (select amount from ShippingPartialPayment P inner join Shipping S on P.idShipping = S.idShipping where S.annulled = 0 and S.currency = 'COP')) t;");
        while ($row = $query->fetch_assoc()) {
            return new Dashboard("Facturado COP", $row["value"]);
        }
    }
    
    public static function getTotalCashUSD() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "select sum(t.amount) as 'value' from ((select amount from BillPartialPayment P inner join Bill B on P.idBill = B.idBill where B.annulled = 0 and B.currency = 'USD') union all (select amount from ShippingPartialPayment P inner join Shipping S on P.idShipping = S.idShipping where S.annulled = 0 and S.currency = 'USD')) t;");
        while ($row = $query->fetch_assoc()) {
            return new Dashboard("Facturado USD", $row["value"]);
        }
    }
    
    public static function getPendingCashCOP() {
        $connectionA = new Connection();
        $queryA = CloudEngineMySQLQuery::execute($connectionA, "select sum(t.value) as 'value' from (select sum(I.amount) as 'value' from Bill B inner join BillItem I on I.idBill = B.idBill where B.annulled = 0 and B.currency = 'COP' union all select sum(S.total) as 'value' from Shipping S where S.annulled = 0 and S.currency = 'COP') as t;");
        while ($row = $queryA->fetch_assoc()) {
            $total = $row["value"];
        }
        
        $connectionB = new Connection();
        $queryB = CloudEngineMySQLQuery::execute($connectionB, "select sum(t.amount) as 'value' from ((select amount from BillPartialPayment P inner join Bill B on P.idBill = B.idBill where B.annulled = 0 and B.currency = 'COP') union all (select amount from ShippingPartialPayment P inner join Shipping S on P.idShipping = S.idShipping where S.annulled = 0 and S.currency = 'COP')) t;");
        while ($row = $queryB->fetch_assoc()) {
            return new Dashboard("Pendiente COP", $total - $row["value"]);
        }
    }
    
    public static function getPendingCashUSD() {
        $connectionA = new Connection();
        $queryA = CloudEngineMySQLQuery::execute($connectionA, "select sum(t.value) as 'value' from (select sum(I.amount) as 'value' from Bill B inner join BillItem I on I.idBill = B.idBill where B.annulled = 0 and B.currency = 'USD' union all select sum(S.total) as 'value' from Shipping S where S.annulled = 0 and S.currency = 'USD') as t;");
        while ($row = $queryA->fetch_assoc()) {
            $total = $row["value"];
        }
        
        $connectionB = new Connection();
        $queryB = CloudEngineMySQLQuery::execute($connectionB, "select sum(t.amount) as 'value' from ((select amount from BillPartialPayment P inner join Bill B on P.idBill = B.idBill where B.annulled = 0 and B.currency = 'USD') union all (select amount from ShippingPartialPayment P inner join Shipping S on P.idShipping = S.idShipping where S.annulled = 0 and S.currency = 'USD')) t;");
        while ($row = $queryB->fetch_assoc()) {
            return new Dashboard("Pendiente USD", $total - $row["value"]);
        }
    }
    
    public static function getActiveLockers() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "select count(*) as 'value' from Customer where active = 1 and deleted = 0;");
        while ($row = $query->fetch_assoc()) {
            return new Dashboard("Clientes activos", $row["value"]);
        }
    }
    
    public static function getInWarehousePurchases() {
        $connection = new Connection();
        $query = CloudEngineMySQLQuery::execute($connection, "select count(*) as 'value' from Purchase where idShipping is null;");
        while ($row = $query->fetch_assoc()) {
            return new Dashboard("En bodega", $row["value"]);
        }
    }
    
}
