<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Name", 100, true));
$service->setCallback(function() use ($service) {
    try {
        ShipmentCompanyDAO::create($service->getParameter("Name")->getValue());
        $service->setResponse("Empresa creada correctamente.");
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();
