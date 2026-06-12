<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdBillItem", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Text", 500, false));
$service->setCallback(function() use ($service) {
    //BillTrackingDAO::create($service->getParameter("Date")->getValue(), $service->getParameter("Text")->getValue(), CloudEngineSession::getSessionObject()->getObject()->getIdSystemUser(), "PUBLIC", $service->getParameter("IdBill")->getValue());
    BillItemTrackingDAO::create($service->getParameter("Text")->getValue(), CloudEngineSession::getSessionObject()->getObject()->getIdSystemUser(), $service->getParameter("Date")->getValue(), $service->getParameter("IdBillItem")->getValue());
    /*$bill = BillDAO::getBillById($service->getParameter("IdBill")->getValue());
    $idCustomer = $shipping->getPurchases()[0]->getCustomer()->getIdCustomer();
    NotificationDAO::create("Movimiento registrado para el envío No. " . $shipping->getShippingNumber() . ": " . $service->getParameter("Text")->getValue(), $idCustomer);*/
    
    $service->setResponse("Novedad registrada correctamente.");
});
$service->publish();
