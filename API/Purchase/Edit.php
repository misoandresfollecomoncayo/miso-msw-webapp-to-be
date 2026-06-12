<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdPurchase", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("LockerNumber", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("TrackingNumber", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Content", 2000, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Store", 36, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Weight", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("Long", 11, false));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("Width", 11, false));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("High", 11, false));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("Quantity", 11, true));
$service->setCallback(function() use ($service) {
    try {
        $customer = CustomerDAO::getCustomerByLocker($service->getParameter("LockerNumber")->getValue());
        PurchaseDAO::update(
            $service->getParameter("IdPurchase")->getValue(),
            $service->getParameter("Date")->getValue(),
            $service->getParameter("Content")->getValue(),
            $service->getParameter("Weight")->getValue(),
            $service->getParameter("Long")->getValue(),
            $service->getParameter("Width")->getValue(),
            $service->getParameter("High")->getValue(),
            $customer->getIdCustomer(),
            $service->getParameter("TrackingNumber")->getValue(),
            $service->getParameter("Store")->getValue(),
            $service->getParameter("Quantity")->getValue(),
            CloudEngineSession::getSessionObject()->getObject()->getIdSystemUser());
        
        $service->setResponse("Mercancía actualizada correctamente.");
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();
