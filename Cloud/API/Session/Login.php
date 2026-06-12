<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterEmail;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterEmail("User", true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Password", 128, true));
$service->setCallback(function() use ($service) {
    $user = UserDAO::getUserByEmail($service->getParameter("User")->getValue());
    
    if (null != $user && $user->getPassword() == md5($service->getParameter("Password")->getValue())) {
        CloudEngineSession::start($user);
        $service->setResponse(PUBLIC_PATH_PLATFORM . "Views/Transversal/Dashboard.php");
    } else {
        sleep(3);
        $service->setException("Usuario y/o clave no válidos");
    }
});
$service->publish();
