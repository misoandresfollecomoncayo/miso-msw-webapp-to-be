<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdUser", 36, true));
$service->setCallback(function() use ($service) {
    SystemUserDAO::deactivate($service->getParameter("IdUser")->getValue());
    $service->setResponse("Usuario desactivado correctamente.");
});
$service->publish();
