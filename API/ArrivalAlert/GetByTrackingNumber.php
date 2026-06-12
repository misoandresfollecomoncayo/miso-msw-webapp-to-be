<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("TrackingNumber", 1000, true));
$service->setCallback(function() use ($service) {
    $arrivalAlert = ArrivalAlertDAO::getArrivalAlertByTrackingNumber($service->getParameter("TrackingNumber")->getValue());
    if ($arrivalAlert != null) {
        $response = array();
        array_push($response, [
            "quantity" => $arrivalAlert->getItems(),
            "detail" => $arrivalAlert->getPurchase(),
            "idStore" => $arrivalAlert->getStore()->getIdStore()
        ]);
        $service->setResponse(json_encode($response));
    } else {
        $service->setException("Alerta no existe.");
    }
});
$service->publish();
