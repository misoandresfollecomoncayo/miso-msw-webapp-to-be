<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\HTTP\CloudEngineRequest;
use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterEmail;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterEmail("Email", true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Password", 128, true));
$service->setCallback(function() use ($service) {
    $access = AccessDAO::getAccessByEmail($service->getParameter("Email")->getValue());
    
    if ($access != null && $access->getPassword() == md5($service->getParameter("Password")->getValue())) {
        if ($access->isActive()) {
            CloudEngineSession::start($access);
            $service->setResponse("Transversal/Dashboard.php");
        } else {
            sleep(3);
            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                $service->setException("La cuenta no está activa, por favor revise su correo electrónico.");
            } else {
                $service->setException("Your account isn't active, please verify your email.");
            }
        }
    } else {
        sleep(3);
        if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
            $service->setException("Usuario o clave inválidos.");
        } else {
            $service->setException("Invalid user or password.");
        }
    }
});
$service->publish();
