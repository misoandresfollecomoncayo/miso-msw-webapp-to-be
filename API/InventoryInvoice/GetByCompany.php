<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Company", 100, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("Page", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Search", 1000, false));
$service->setCallback(function() use ($service) {
    $result = new stdClass();
    
    $indicators = InventoryInvoiceDAO::getIndicators($service->getParameter("Company")->getValue());;
    $result->products = InventoryInvoiceDAO::getBySellingCompanyProductsNumber($service->getParameter("Company")->getValue());
    $result->utilities = $indicators->utility;
    $result->paid = $indicators->paid;
    $result->pending = $indicators->pending;
    $result->pages = InventoryInvoiceDAO::getBySellingCompanyPagesNumber($service->getParameter("Company")->getValue());
    $result->items = InventoryInvoiceDAO::getBySellingCompany($service->getParameter("Company")->getValue(), $service->getParameter("Page")->getValue(), $service->getParameter("Search")->getValue());
    
    $service->setResponse($result);
});
$service->publish();