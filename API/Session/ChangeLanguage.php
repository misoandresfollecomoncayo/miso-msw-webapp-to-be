<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Language", 45, true));
$service->setCallback(function() use ($service) {
    $sessionUser = CloudEngineSession::getSessionObject()->getObject();
    
    if ($sessionUser != null) {
        CustomerDAO::updateLanguage($sessionUser->getIdCustomer(), $service->getParameter("Language")->getValue());
        $service->setResponse("Language updated successfully.");
    } else {
        $service->setException("No session.");
    }
});
$service->publish();
