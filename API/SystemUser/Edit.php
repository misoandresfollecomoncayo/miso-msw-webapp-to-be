<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdSystemUser", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Names", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Email", 320, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdRole", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("SendRequestShipmentNotification", 1, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("SendAlertArrivalNotification", 1, true));
$service->setCallback(function() use ($service) {
    try {
        SystemUserDAO::update($service->getParameter("IdSystemUser")->getValue(), $service->getParameter("Names")->getValue(), $service->getParameter("Email")->getValue(), $service->getParameter("IdRole")->getValue(), $service->getParameter("SendRequestShipmentNotification")->getValue(), $service->getParameter("SendAlertArrivalNotification")->getValue());
        $service->setResponse("Usuario actualizado correctamente.");
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();
