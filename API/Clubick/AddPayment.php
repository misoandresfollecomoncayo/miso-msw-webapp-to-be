<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterDate;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterEmail;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterBoolean;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterDate("Date", true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Amount", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Method", 45, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdClubick", 36, true));
$service->setCallback(function() use ($service) {
    try {
        ClubickPaymentDAO::add(
            $service->getParameter("Date")->getValue(),
            $service->getParameter("Amount")->getValue(),
            $service->getParameter("Method")->getValue(),
            CloudEngineSession::getSessionObject()->getObject()->getNames(),
            $service->getParameter("IdClubick")->getValue()
        );
        $service->setResponse("Pago registrado correctamente.");
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();