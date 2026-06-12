<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterEmail;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Current", 128, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("New", 128, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Confirm", 128, true));
$service->setCallback(function() use ($service) {
    $sessionUser = CloudEngineSession::getSessionObject();
    
    if ($sessionUser != null) {
        if ($sessionUser->getPassword() == md5($service->getParameter("Current")->getValue())) {
            if (md5($service->getParameter("New")->getValue()) == md5($service->getParameter("Confirm")->getValue())) {
                $sessionUser->updatePassword($service->getParameter("New")->getValue());
                if (CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER &&
                    CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH) {
                    $service->setResponse("Password updated successfully.");
                } else {
                    $service->setResponse("Clave actualizada correctamente.");
                }
            } else {
                if (CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER &&
                    CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH) {
                    $service->setException("Invalid password confirmation.");
                } else {
                    $service->setException("La confirmación no es válida.");
                }
            }
        } else {
            if (CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER &&
                CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH) {
                $service->setException("Invalid current password.");
            } else {
                $service->setException("La clave actual no es válida.");
            }
        }
    } else {
        if (CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER &&
            CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH) {
            $service->setException("Debe iniciar sesión.");
        } else {
            $service->setException("Debe iniciar sesión.");
        }
    }
});
$service->publish();
