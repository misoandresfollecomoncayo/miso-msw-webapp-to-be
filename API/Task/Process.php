<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdTask", 36, true));
$service->setCallback(function() use ($service) {
    $sessionUser = CloudEngineSession::getSessionObject();
    TaskDAO::process($sessionUser->getIdRegister(), $service->getParameter("IdTask")->getValue());
    $service->setResponse("Tarea procesada correctamente.");
});
$service->publish();
