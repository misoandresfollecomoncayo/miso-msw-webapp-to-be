<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterDate;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterEmail;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterBoolean;
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
    try {
        InventoryDAO::create(
            $service->getParameter("Product")->getValue(),
            $service->getParameter("TRM")->getValue(),
            $service->getParameter("USDPrice")->getValue(),
            $service->getParameter("COPPrice")->getValue(),
            $service->getParameter("InternationalShippingPrice")->getValue(),
            $service->getParameter("NationalShippingPrice")->getValue(),
            $service->getParameter("TotalCost")->getValue(),
            $service->getParameter("SalePrice")->getValue(),
            $service->getParameter("Utility")->getValue()
        );
        $service->setResponse("Registro almacenado correctamente.");
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();