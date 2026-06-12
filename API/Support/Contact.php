<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Message", 10000, true));
$service->setCallback(function() use ($service) {
    $customer = CloudEngineSession::getSessionObject()->getObject();
    EmailEngine::customerContactMessage($customer, $service->getParameter("Message")->getValue());
    
    if ($customer->getLanguage() == Customer::LANGUAGE_SPANISH) {
        $service->setResponse("Mensaje enviado correctamente."); 
    } else {
        $service->setResponse("Message sent successfully."); 
    }
});
$service->publish();