<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdCustomer", 36, true));
$service->setCallback(function() use ($service) {
    $customer = CustomerDAO::getCustomerById($service->getParameter("IdCustomer")->getValue());
    
    if ($customer != null) {
        CustomerDAO::active($customer->getIdCustomer());
        $service->setResponse("Cliente activado correctamente.");
    } else {
        $service->setException("Cliente no existe.");
    }
});
$service->publish();
