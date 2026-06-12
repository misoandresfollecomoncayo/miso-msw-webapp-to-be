<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdBill", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdPaymentMethod", 36, true));
$service->setCallback(function() use ($service) {
    BillDAO::pay($service->getParameter("IdBill")->getValue(), $service->getParameter("IdPaymentMethod")->getValue());
    $service->setResponse("Pago registrado correctamente.");
});
$service->publish();
