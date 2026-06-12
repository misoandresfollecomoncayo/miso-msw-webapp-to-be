<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Bill", 100, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Customer", 100, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Items", 1000 * 100, true));
$service->setCallback(function() use ($service) {
    $UUID = EcuadorDAO::create($service->getParameter("Bill")->getValue(), $service->getParameter("Customer")->getValue());
    
    $items = json_decode($service->getParameter("Items")->getValue());
    
    foreach ($items as $i) {
        EcuadorDAO::insertItem($i->quantity, $i->sequence, $i->description, $UUID);
    }
    
    $service->setResponse("Registros creados correctamente.");
});
$service->publish();