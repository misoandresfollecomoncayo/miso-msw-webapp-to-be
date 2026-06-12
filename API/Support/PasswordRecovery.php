<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\HTTP\CloudEngineRequest;
use Cloud\Engine\PHP\Utils\CloudEngineGoogleRecaptcha;
use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterEmail;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterEmail("Email", true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Captcha", 1024, true));
$service->setCallback(function() use ($service) {
    if (CloudEngineGoogleRecaptcha::isValid($service->getParameter("Captcha")->getValue(), GOOGLE_RECAPTCHA_PRIVATE_KEY)) {
        $access = AccessDAO::getAccessByEmail($service->getParameter("Email")->getValue());

        if ($access != null) {
            $token = TokenDAO::create($access->getIdRegister(), $access->getType());
            EmailEngine::passwordRecovery($token);
        }

        if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
            $service->setResponse("Solicitud enviada correctamente.");
        } else {
            $service->setResponse("Request sent successfully.");
        }
    } else {
        if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
            $service->setException("Google reCaptcha inválido.");
        } else {
            $service->setException("Invalid Google reCaptcha.");
        }
    }
});
$service->publish();
