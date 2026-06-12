<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("TrackingNumber", 200, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Purchase", 10000, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("Items", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdStore", 36, true));
$service->setCallback(function() use ($service) {
    $sessionCustomer = CloudEngineSession::getSessionObject()->getObject();
    
    if ($sessionCustomer != null) {
        ArrivalAlertDAO::create($service->getParameter("TrackingNumber")->getValue(), $service->getParameter("Purchase")->getValue(), $service->getParameter("Items")->getValue(), $service->getParameter("IdStore")->getValue(), CloudEngineSession::getSessionObject()->getIdRegister());
        
        $systemUsersToNotify = SystemUserDAO::getSystemUsersSendAlertArrivalNotification();
        
        $requestString = "Nueva alerta de llegada:<br/><br/>";
        $requestString .= "<b>Casillero:</b> " . $sessionCustomer->getLockerNumber() . "<br/>";
        $requestString .= "<b>Cliente:</b> " . $sessionCustomer->getNames() . "<br/>";
        $requestString .= "<b>Rastreo:</b> " . $service->getParameter("TrackingNumber")->getValue() . "<br/>";
        $requestString .= "<b>Detalle:</b> " . $service->getParameter("Purchase")->getValue() . "<br/>";
        $requestString .= "<b>Cantidad:</b> " . $service->getParameter("Items")->getValue() . "<br/>";
        $requestString .= "<b>Tienda:</b> " . StoreDAO::getStoreById($service->getParameter("IdStore")->getValue())->getName();
        
        foreach ($systemUsersToNotify as $s) {
            NotificationDAO::create($requestString, $s->getIdSystemUser());
            EmailEngine::arrivalAlert($s, $requestString);
        }
        
        $service->setResponse("Alerta registrada correctamente.");
    }
});
$service->publish();