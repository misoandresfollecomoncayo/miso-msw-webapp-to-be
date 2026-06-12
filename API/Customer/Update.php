<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterEmail;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Language", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdCity", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Address", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Telephone", 45, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Telephone2", 45, false));
$service->addParameterObj(new CloudEngineWebServiceParameterEmail("Email", true));
$service->setCallback(function() use ($service) {
    $sessionUser = CloudEngineSession::getSessionObject();
    
    if ($sessionUser != null) {
        CustomerDAO::updateSession($sessionUser->getIdRegister(), $service->getParameter("Language")->getValue(), $service->getParameter("IdCity")->getValue(), $service->getParameter("Address")->getValue(), $service->getParameter("Telephone")->getValue(), $service->getParameter("Telephone2")->getValue(), $service->getParameter("Email")->getValue());
        
        switch ($service->getParameter("Language")->getValue()) {
            case "SPANISH":
                $service->setResponse("Perfil actualizado correctamente.");
                break;
            default:
                $service->setResponse("Profile updated successfully.");
                break;
        }
        
    } else {
        $service->setException("Debe iniciar sesión.");
    }
});
$service->publish();
