<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("User", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Message", 10000, true));
$service->setCallback(function() use ($service) {
    $sessionUser = CloudEngineSession::getSessionObject();
    
    if ($service->getParameter("User")->getValue() == "*Customers") {
        $customers = CustomerDAO::getCustomers();
        foreach ($customers as $c) {
            NotificationDAO::create($service->getParameter("Message")->getValue(), $c->getIdCustomer());
        }
    } else if ($service->getParameter("User")->getValue() == "*Administrators") {
        $systemUsers = SystemUserDAO::getSystemUsers();
        foreach ($systemUsers as $u) {
            NotificationDAO::create($sessionUser->getObject()->getNames() . ", escribió: " . $service->getParameter("Message")->getValue(), $u->getIdSystemUser());
        }
    } else {
        NotificationDAO::create($sessionUser->getObject()->getNames() . ", escribió: " . $service->getParameter("Message")->getValue(), $service->getParameter("User")->getValue());
    }
    
    $service->setResponse("Notificación enviada correctamente.");
});
$service->publish();
