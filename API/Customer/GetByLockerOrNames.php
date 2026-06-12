<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Search", 256, true));
$service->setCallback(function() use ($service) {
    $customers = CustomerDAO::getCustomersByLockerOrNames($service->getParameter("Search")->getValue());
    if (count($customers) > 0) {
        $response = array();
        foreach ($customers as $customer) {
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
        }
        $service->setResponse(json_encode($response));
    } else {
        $service->setException("No existen resultados.");
    }
});
$service->publish();
