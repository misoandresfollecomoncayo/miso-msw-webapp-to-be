<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterNumeric;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Id", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("TRM", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Detail", 1000 * 100, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("RealCostPurchaseUSD", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("FreightSaleUSD", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("SalePriceCOP", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("FreightUSD", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Reference", 1000, false));
$service->setCallback(function() use ($service) {
    $UUID = PurchasesAgentItemDAO::edit(
        $service->getParameter("Id")->getValue(),
        $service->getParameter("Date")->getValue(),
        $service->getParameter("TRM")->getValue(),
        $service->getParameter("Detail")->getValue(),
        $service->getParameter("RealCostPurchaseUSD")->getValue(),
        $service->getParameter("FreightSaleUSD")->getValue(),
        $service->getParameter("SalePriceCOP")->getValue(),
        $service->getParameter("FreightUSD")->getValue(),
        $service->getParameter("Reference")->getValue());
    $service->setResponse(json_encode(PurchasesAgentItemDAO::getById($service->getParameter("Id")->getValue())));
});
$service->publish();