<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;
use Cloud\Engine\PHP\MySQL\Helpers;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Id", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Detail", 10000, true));
$service->setCallback(function() use ($service) {
    try {
        InventoryTrackingDAO::create(
            $service->getParameter("Date")->getValue(),
            $service->getParameter("Detail")->getValue(),
            $service->getParameter("Id")->getValue(),
            CloudEngineSession::getSessionObject()->getObject()->getNames()
        );
        $service->setResponse("Registro almacenado correctamente.");
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();