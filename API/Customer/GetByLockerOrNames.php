<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Search", 256, true));
$service->setCallback(function() use ($service) {
    // ADAPTER -> microservicio nuevo (GET /api/customers?search=). Misma forma de respuesta.
    $search = $service->getParameter("Search")->getValue();
    $res  = MswApiClient::request("GET", "/api/customers?limit=100&search=" . rawurlencode($search));
    $rows = (MswApiClient::isOk($res) && isset($res["body"]["data"])) ? $res["body"]["data"] : array();
    if (count($rows) > 0) {
        $response = array();
        foreach ($rows as $c) {
            $loc = MswApiClient::resolveCountryCity($c["cityId"]);
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
        }
        $service->setResponse(json_encode($response));
    } else {
        $service->setException("No existen resultados.");
    }
});
$service->publish();
