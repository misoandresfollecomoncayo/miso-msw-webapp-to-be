<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterDate;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterEmail;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterBoolean;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Id", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Name", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("DocumentNumber", 100, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdCity", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Address", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("PhoneNumber", 45, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Email", 320, true));
$service->setCallback(function() use ($service) {
    try {
        InventoryCustomerDAO::update(
            $service->getParameter("Id")->getValue(),
            $service->getParameter("Name")->getValue(),
            $service->getParameter("DocumentNumber")->getValue(),
            $service->getParameter("IdCity")->getValue(),
            $service->getParameter("Address")->getValue(),
            $service->getParameter("PhoneNumber")->getValue(),
            $service->getParameter("Email")->getValue()
        );
        $service->setResponse("Cliente ventas actualizado correctamente.");
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();