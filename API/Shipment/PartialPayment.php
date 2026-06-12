<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Amount", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdPaymentMethod", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdShipping", 36, true));
$service->setCallback(function() use ($service) {
    ShippingPartialPaymentDAO::create($service->getParameter("Date")->getValue(), $service->getParameter("Amount")->getValue(), $service->getParameter("IdPaymentMethod")->getValue(), $service->getParameter("IdShipping")->getValue(), CloudEngineSession::getSessionObject()->getIdRegister());
    $service->setResponse("Pago registrado correctamente.");
});
$service->publish();
