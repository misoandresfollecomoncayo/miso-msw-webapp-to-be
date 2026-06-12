<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("LockerNumber", 11, true));
$service->setCallback(function() use ($service) {
    $customer = CustomerDAO::getCustomerByLocker($service->getParameter("LockerNumber")->getValue());
    if ($customer != null) {
        $response = array();
        array_push($response, [
            "names" => $customer->getNames(),
            "email" => $customer->getEmail(),
            "country" => $customer->getCity()->getCountry()->getName(),
            "city" => $customer->getCity()->getName(),
            "address" => $customer->getAddress(),
            "phone" => $customer->getTelephone(),
            "phone2" => $customer->getTelephone2(),
            "lockerNumber" => $customer->getLockerNumber()
        ]);
        $service->setResponse(json_encode($response));
    } else {
        $service->setException("Casillero no existe.");
    }
});
$service->publish();
