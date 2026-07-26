<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("LockerNumber", 11, true));
$service->setCallback(function() use ($service) {
    // ADAPTER -> microservicio nuevo (GET /api/customers/by-locker/{locker}).
    // Se reconstruye la MISMA forma de respuesta que consumen Bills/Purchases.
    $locker = $service->getParameter("LockerNumber")->getValue();
    $res = MswApiClient::request("GET", "/api/customers/by-locker/" . rawurlencode($locker));
    if (MswApiClient::isOk($res) && is_array($res["body"]) && isset($res["body"]["id"])) {
        $c   = $res["body"];
        $loc = MswApiClient::resolveCountryCity($c["cityId"]);
        $response = array();
        array_push($response, array(
            "names"        => $c["names"],
            "email"        => $c["email"],
            "country"      => $loc["country"],
            "city"         => $loc["city"],
            "address"      => $c["address"],
            "phone"        => $c["telephone"],
            "phone2"       => isset($c["telephone2"]) ? $c["telephone2"] : "",
            "lockerNumber" => $c["lockerNumber"]
        ));
        $service->setResponse(json_encode($response));
    } else {
        $service->setException("Casillero no existe.");
    }
});
$service->publish();
