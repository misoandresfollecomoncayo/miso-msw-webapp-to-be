<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\HTTP\CloudEngineRequest;
use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Token", 64, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("New", 128, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Confirm", 128, true));
$service->setCallback(function() use ($service) {
    $token = TokenDAO::getTokenById($service->getParameter("Token")->getValue());
    $new = $service->getParameter("New")->getValue();
    $confirm = $service->getParameter("Confirm")->getValue();
    
    if ($token != null && !$token->isUsed()) {
        if (md5($new) == md5($confirm)) {
            TokenDAO::consume($token);
            
            if ($token->getType() == Token::TYPE_CUSTOMER) {
                CustomerDAO::updatePassword($token->getObject()->getIdCustomer(), $new);
            } else {
                SystemUserDAO::updatePassword($token->getObject()->getIdSystemUser(), $new);
            }
            $service->setResponse(PUBLIC_PATH_PLATFORM);
        } else {
            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                $service->setException("La nueva clave y la confirmación deben ser iguales.");
            } else {
                $service->setException("New password and confirm must be equals.");
            }
        }
    } else {
        if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
            $service->setException("Token inválido.");
        } else {
            $service->setException("Invalid token.");
        }
    }
});
$service->publish();
