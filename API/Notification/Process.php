<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdNotification", 36, true));
$service->setCallback(function() use ($service) {
    NotificationDAO::process($service->getParameter("IdNotification")->getValue());
    $service->setResponse("Notificación revisada correctamente.");
});
$service->publish();
