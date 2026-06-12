<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterDate;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterEmail;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterBoolean;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Names", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Gender", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterDate("Birthdate", true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Language", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdDocumentType", 36, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("DocumentNumber", 100, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdCity", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Address", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Telephone", 45, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Telephone2", 45, false));
$service->addParameterObj(new CloudEngineWebServiceParameterEmail("Email", true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Password", 128, true));
$service->addParameterObj(new CloudEngineWebServiceParameterBoolean("Notify", false));
$service->setCallback(function() use ($service) {
    try {
        CustomerDAO::createFromAdministrator(
            $service->getParameter("Names")->getValue(),
            $service->getParameter("Gender")->getValue(),
            $service->getParameter("Birthdate")->getValue(),
            $service->getParameter("Language")->getValue(),
            $service->getParameter("IdDocumentType")->getValue(),
            $service->getParameter("DocumentNumber")->getValue(),
            $service->getParameter("IdCity")->getValue(),
            $service->getParameter("Address")->getValue(),
            $service->getParameter("Telephone")->getValue(),
            $service->getParameter("Telephone2")->getValue(),
            $service->getParameter("Email")->getValue(),
            $service->getParameter("Password")->getValue(),
            $service->getParameter("Notify")->getValue()
        );
        $service->setResponse("Cliente creado correctamente.");
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();