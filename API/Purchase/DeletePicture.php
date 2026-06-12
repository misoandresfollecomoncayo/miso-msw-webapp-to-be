<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdPurchasePicture", 36, true));
$service->setCallback(function() use ($service) {
    if (file_exists(PRIVATE_PATH_UPLOADS . $service->getParameter("IdPurchasePicture")->getValue())) {
        unlink(PRIVATE_PATH_UPLOADS . $service->getParameter("IdPurchasePicture")->getValue());
        PurchasePictureDAO::delete($service->getParameter("IdPurchasePicture")->getValue());
        $service->setResponse("Foto eliminada correctamente.");
    } else {
        $service->setException("Foto no existe.");
    }
});
$service->publish();
