<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Description", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdBillItem", 36, true));
$service->setCallback(function() use ($service) {
    //TaskDAO::create($service->getParameter("Title")->getValue(),$service->getParameter("Description")->getValue(),$service->getParameter("Priority")->getValue(),$service->getParameter("IdCountry")->getValue(),$service->getParameter("Date")->getValue());
    BillItemTrackingDAO::create($service->getParameter("Description")->getValue(), CloudEngineSession::getSessionObject()->getObject()->getIdSystemUser(), $service->getParameter("Date")->getValue(), $service->getParameter("IdBillItem")->getValue());
    $service->setResponse("Trazabilidad creada correctamente.");
});
$service->publish();
