<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("StartDate", 10, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("EndDate", 10, false));
$service->setCallback(function() use ($service) {
    echo json_encode(PastoDAO::get());
});
$service->publish();