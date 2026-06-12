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
$service->addParameterObj(new CloudEngineWebServiceParameterText("Id", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterDate("Date", true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Customer", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("CustomerDocument", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("CustomerAddress", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("CustomerPhone", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Product", 1000, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("TRM", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("USDPrice", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("COPPrice", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("UniexpressShippingPrice", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("TotalPrice", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("SalePrice", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("NationalShippingPrice", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("TotalToPay", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Status", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Utility", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("UtilitySantiago", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("UtilityJulian", 18, true));
$service->setCallback(function() use ($service) {
    try {
        $id = ClubickDAO::save(
            $service->getParameter("Id")->getValue(),
            $service->getParameter("Date")->getValue(),
            $service->getParameter("Customer")->getValue(),
            $service->getParameter("CustomerDocument")->getValue(),
            $service->getParameter("CustomerAddress")->getValue(),
            $service->getParameter("CustomerPhone")->getValue(),
            $service->getParameter("Product")->getValue(),
            $service->getParameter("TRM")->getValue(),
            $service->getParameter("USDPrice")->getValue(),
            $service->getParameter("COPPrice")->getValue(),
            $service->getParameter("UniexpressShippingPrice")->getValue(),
            $service->getParameter("TotalPrice")->getValue(),
            $service->getParameter("SalePrice")->getValue(),
            $service->getParameter("NationalShippingPrice")->getValue(),
            $service->getParameter("TotalToPay")->getValue(),
            $service->getParameter("Status")->getValue(),
            $service->getParameter("Utility")->getValue(),
            $service->getParameter("UtilitySantiago")->getValue(),
            $service->getParameter("UtilityJulian")->getValue()
        );
        $service->setResponse($id);
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();