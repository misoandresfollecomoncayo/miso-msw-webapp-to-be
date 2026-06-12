<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdShipping", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("AdditionalValue", 10, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("AdditionalValueDescription", 500, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("PaymentMethod", 36, true));
$service->setCallback(function() use ($service) {
    ShippingDAO::payment($service->getParameter("IdShipping")->getValue(),$service->getParameter("AdditionalValue")->getValue(),$service->getParameter("AdditionalValueDescription")->getValue(),$service->getParameter("PaymentMethod")->getValue());
    $service->setResponse("Pago registrado correctamente.");
});
$service->publish();
