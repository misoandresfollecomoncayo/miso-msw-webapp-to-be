<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("FromLockerNumber", 11, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("From", 500, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("FromAddress", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("FromPhone", 45, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("To", 500, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("ToAddress", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("ToPhone", 45, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("ToCountry", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("TemporaryPayment", 11, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Currency", 3, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdPaymentMethod", 36, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdShipmentCompany", 36, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Items", 10000, true));
$service->setCallback(function() use ($service) {
    $customer = CustomerDAO::getCustomerByLocker($service->getParameter("FromLockerNumber")->getValue());
    $idCustomer = null;
    
    if ($customer != null) {
        $idCustomer = $customer->getIdCustomer();
    }
    
    $UUID = BillDAO::create(
        $service->getParameter("Date")->getValue(),
        $idCustomer,
        $service->getParameter("From")->getValue(),
        $service->getParameter("FromAddress")->getValue(),
        $service->getParameter("FromPhone")->getValue(),
        $service->getParameter("To")->getValue(),
        $service->getParameter("ToAddress")->getValue(),
        $service->getParameter("ToPhone")->getValue(),
        $service->getParameter("ToCountry")->getValue(),
        $service->getParameter("Currency")->getValue(),
        $service->getParameter("IdShipmentCompany")->getValue()
    );
    $items = json_decode($service->getParameter("Items")->getValue());
    
    foreach ($items as $i) {
        BillItemDAO::create($i->description, $i->box, $i->weight, $i->total, $UUID);
    }
    
    // Save partial payment
    if ($service->getParameter("TemporaryPayment")->getValue() != "" &&
        $service->getParameter("IdPaymentMethod")->getValue() != "") {
        BillPartialPaymentDAO::create($service->getParameter("Date")->getValue(), $service->getParameter("TemporaryPayment")->getValue(), $service->getParameter("IdPaymentMethod")->getValue(), $UUID, CloudEngineSession::getSessionObject()->getIdRegister());
    }
    
    $service->setResponse($UUID);
});
$service->publish();