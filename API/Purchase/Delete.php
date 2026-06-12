<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;

use Cloud\Engine\PHP\HTTP\CloudEngineSession;

use Cloud\Engine\PHP\MySQL\Helpers;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Id", 36, true));
$service->setCallback(function() use ($service) {
    PurchaseDAO::delete($service->getParameter("Id")->getValue());
    $service->setResponse("Registro eliminado correctamente");
});
$service->publish();
