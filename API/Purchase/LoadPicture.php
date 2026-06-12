<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

use Cloud\Engine\PHP\MySQL\Helpers;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdPurchase", 36, true));
$service->setCallback(function() use ($service) {
    $file = isset($_FILES["File"]) ? $_FILES["File"] : null;
    if ($file != null) {
        $fileUUID = Helpers::UUID();
        move_uploaded_file($file["tmp_name"], PRIVATE_PATH_UPLOADS . $fileUUID);
        PurchasePictureDAO::create($fileUUID, $service->getParameter("IdPurchase")->getValue());
        $service->setResponse("Foto cargada correctamente.");
    } else {
        $service->setResponse("Error al cargar la foto.");
    }
});
$service->publish();
