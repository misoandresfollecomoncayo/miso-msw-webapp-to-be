<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Current", 128, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("New", 128, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Confirm", 128, true));
$service->setCallback(function() use ($service) {
    if (null != CloudEngineSession::getSessionObject()) {
        $md5Current = md5($service->getParameter("Current")->getValue());
        $md5New = md5($service->getParameter("New")->getValue());
        $md5Confirm = md5($service->getParameter("Confirm")->getValue());
        
        $sessionUser = CloudEngineSession::getSessionObject();
        if ($sessionUser->getPassword() == $md5Current) {
            if ($md5New == $md5Confirm) {
                UserDAO::changePassword($sessionUser->getIdUser(), $md5New);
                $service->setResponse("Clave actualizada correctamente.");
            } else {
                $service->setException("La nueva clave y la confirmación no son iguales, vuelva a intentarlo.");
            }
        } else {
            $service->setException("La clave actual no es correcta, vuelva a intenterlo.");
        }
    }
});
$service->publish();
