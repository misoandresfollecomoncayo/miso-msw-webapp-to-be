<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdPurchase", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("NetWeight", 11, true));
$service->setCallback(function() use ($service) {
    PurchaseDAO::updateNetWeight($service->getParameter("IdPurchase")->getValue(), $service->getParameter("NetWeight")->getValue());
    $service->setResponse("Peso neto actualizado correctamente.");
});
$service->publish();
