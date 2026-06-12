<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterDate;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Description", 10000, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdProjectModule", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdProjectActor", 36, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Priority", 20, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Complexity", 20, true));
$service->addParameterObj(new CloudEngineWebServiceParameterDate("StartDate", true));
$service->addParameterObj(new CloudEngineWebServiceParameterDate("EndDate", true));
$service->setCallback(function() use ($service) {
    if (null != CloudEngineSession::getSessionObject()) {
        ProjectRequirementDAO::add($service->getParameter("Description")->getValue(), $service->getParameter("IdProjectModule")->getValue(), $service->getParameter("IdProjectActor")->getValue(), $service->getParameter("Priority")->getValue(), $service->getParameter("Complexity")->getValue(), $service->getParameter("StartDate")->getValue(), $service->getParameter("EndDate")->getValue(), CloudEngineSession::getSessionObject()->getIdUser());
        $service->setResponse("Requerimiento agregado correctamente.");
    }
});
$service->publish();
