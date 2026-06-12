<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\Utils\CloudEngineGoogleRecaptcha;
use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterDate;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterEmail;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;
use Cloud\Engine\PHP\HTTP\CloudEngineRequest;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Name", 100, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Email", 254, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("City", 45, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Phone", 45, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Captcha", 1024, true));
$service->setCallback(function() use ($service) {
    try {
        if (CloudEngineGoogleRecaptcha::isValid($service->getParameter("Captcha")->getValue(), GOOGLE_RECAPTCHA_PRIVATE_KEY)) {
            GiveawayDAO::create(
                $service->getParameter("Name")->getValue(),
                $service->getParameter("Email")->getValue(),
                $service->getParameter("City")->getValue(),
                $service->getParameter("Phone")->getValue()
            );
            $service->setResponse("Gracias por registrarte. ¡Mucha suerte!");
        } else {
            $service->setException("Google reCaptcha inválido.");
        }
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();
