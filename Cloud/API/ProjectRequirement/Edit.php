<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdProjectRequirement", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Description", 10000, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdProjectModule", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("State", 20, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdActor", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Priority", 20, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Complexity", 20, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("StartDate", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("EndDate", 10, true));
$service->setCallback(function() use ($service) {
    if (null != CloudEngineSession::getSessionObject()) {
        ProjectRequirementDAO::edit(
        $service->getParameter("IdProjectRequirement")->getValue(),
        $service->getParameter("Description")->getValue(),
        $service->getParameter("IdProjectModule")->getValue(),
        $service->getParameter("State")->getValue(),
        $service->getParameter("IdActor")->getValue(),
        $service->getParameter("Priority")->getValue(),
        $service->getParameter("Complexity")->getValue(),
        $service->getParameter("StartDate")->getValue(),
        $service->getParameter("EndDate")->getValue(),
        CloudEngineSession::getSessionObject()->getIdUser());
        $service->setResponse("Requerimiento actualizado correctamente.");
    }
});
$service->publish();
