<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Product", 1000, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("TRM", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("USDPrice", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("COPPrice", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("InternationalShippingPrice", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("NationalShippingPrice", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("TotalCost", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("SalePrice", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Utility", 18, true));
$service->setCallback(function() use ($service) {
    // ADAPTER -> microservicio nuevo (POST /api/inventory). Frontend sin cambios.
    // COPPrice/TotalCost/Utility los calcula la API nueva; no se envían.
    $body = array(
        "product"           => $service->getParameter("Product")->getValue(),
        "trm"               => floatval($service->getParameter("TRM")->getValue()),
        "usdPrice"          => floatval($service->getParameter("USDPrice")->getValue()),
        "intlShippingPrice" => floatval($service->getParameter("InternationalShippingPrice")->getValue()),
        "natShippingPrice"  => floatval($service->getParameter("NationalShippingPrice")->getValue()),
        "salePrice"         => floatval($service->getParameter("SalePrice")->getValue())
    );
    $res = MswApiClient::request("POST", "/api/inventory", $body);
    if (MswApiClient::isOk($res)) {
        $service->setResponse("Registro almacenado correctamente.");
    } else {
        $service->setException(MswApiClient::errorMessage($res, "No se pudo almacenar el registro."));
    }
});
$service->publish();
