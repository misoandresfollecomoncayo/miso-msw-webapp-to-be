<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;

$service = new CloudEngineWebService();
$service->setType(CloudEngineWebService::TYPE_RAW);
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdCountry", 36, true));
$service->setCallback(function() use ($service) {
    $country = CountryDAO::getCountryById($service->getParameter("IdCountry")->getValue());
    if ($country != null) {
        $cities = $country->getCities();
        $response = array();
        
        foreach($cities as $c) {
            array_push($response, [
                "id" => $c->getIdCity(),
                "name" => $c->getName()
            ]);
        }
        
        $service->setResponse(json_encode($response));
    } else {
        $service->setException("País no existe.");
    }
});
$service->publish();
