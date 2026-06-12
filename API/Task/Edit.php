<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdTask", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Title", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Description", 10000, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Priority", 1, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdCountry", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdWarehouse", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Status", 45, true));
$service->setCallback(function() use ($service) {
    TaskDAO::update($service->getParameter("IdTask")->getValue(),$service->getParameter("Title")->getValue(),$service->getParameter("Description")->getValue(),$service->getParameter("Priority")->getValue(),$service->getParameter("IdCountry")->getValue(),$service->getParameter("IdWarehouse")->getValue(),$service->getParameter("Date")->getValue(),$service->getParameter("Status")->getValue());
    $service->setResponse("Tarea actualizada correctamente.");
});
$service->publish();
