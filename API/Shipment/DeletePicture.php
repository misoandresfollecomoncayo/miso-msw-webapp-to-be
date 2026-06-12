<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Id", 36, true));
$service->setCallback(function() use ($service) {
    $path = PRIVATE_PATH_UPLOADS . "Invoices/" . $service->getParameter("Id")->getValue();
    if (file_exists($path)) {
        unlink($path);
        $service->setResponse("Foto eliminada correctamente.");
    } else {
        $service->setException("Foto no existe.");
    }
});
$service->publish();
