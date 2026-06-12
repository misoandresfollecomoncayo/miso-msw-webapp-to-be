<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Purchases", 120000, true));
$service->setCallback(function() use ($service) {
    $sessionCustomer = CloudEngineSession::getSessionObject()->getObject();
    
    if ($sessionCustomer != null) {
        $systemUsersToNotify = SystemUserDAO::getSystemUsersSendRequestShipmentNotification();
        $purchases = json_decode($service->getParameter("Purchases")->getValue());
        
        $requestString = "Nueva solicitud de envío:<br/><br/>";
        $requestString .= "<b>Casillero:</b> " . $sessionCustomer->getLockerNumber() . "<br/>";
        $requestString .= "<b>Cliente:</b> " . $sessionCustomer->getNames() . "<br/>";
        
        $table = "<table border='1' class='table width-100'>";
        $table .= "<thead>";
        $table .= "<tr>";
        $table .= "<th>Contenido</th>";
        $table .= "<th>Tracking No.</th>";
        $table .= "</tr>";
        $table .= "</thead>";
        $table .= "<tbody>";
        
        foreach ($purchases as $p) {
            $purchase = PurchaseDAO::getPurchaseById($p);
            PurchaseDAO::request($p);
            $table .= "<tr>";
            $table .= "<td>" . $purchase->getContent() . "</td>";
            $table .= "<td>" . $purchase->getTrackingNumber() . "</td>";
            $table .= "</tr>";
        }
        
        $table .= "</tbody>";
        $table .= "</table>";
        
        $requestString .= $table;
        
        foreach ($systemUsersToNotify as $s) {
            NotificationDAO::create($requestString, $s->getIdSystemUser());
            EmailEngine::requestShipment($s, $requestString);
        }
        
        $service->setResponse("Solicitud enviada correctamente");
    }
});
$service->publish();
