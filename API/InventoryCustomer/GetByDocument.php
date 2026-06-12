<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Document", 100, true));
$service->setCallback(function() use ($service) {
    $customer = InventoryCustomerDAO::getByDocumentNumber($service->getParameter("Document")->getValue());
    if ($customer != null) {
        $response = array();
        array_push($response, [
            "id" => $customer->id,
            "names" => $customer->name,
            "documentNumber" => $customer->documentNumber,
            "country" => $customer->getCity()->getCountry()->getName(),
            "city" => $customer->getCity()->getName(),
            "address" => $customer->address ,
            "phoneNumber" => $customer->phoneNumber,
            "email" => $customer->email
        ]);
        $service->setResponse(json_encode($response));
    } else {
        $service->setException("Cliente no existe.");
    }
});
$service->publish();
