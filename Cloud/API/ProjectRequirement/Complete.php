<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdProjectRequirement", 36, true));
$service->setCallback(function() use ($service) {
    if (null != CloudEngineSession::getSessionObject()) {
        ProjectRequirementDAO::complete($service->getParameter("IdProjectRequirement")->getValue(), CloudEngineSession::getSessionObject()->getIdUser());
        $service->setResponse("Requerimiento completado correctamente.");
    }
});
$service->publish();
