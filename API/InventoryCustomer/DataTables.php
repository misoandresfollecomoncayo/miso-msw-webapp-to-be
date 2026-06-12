<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->setType(CloudEngineWebService::TYPE_RAW);
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("draw", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("start", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("length", 11, true));
$service->setCallback(function() use ($service) {
    $customers = InventoryCustomerDAO::getCustomersDataTables($service->getParameter("start")->getValue(),$service->getParameter("length")->getValue(),$_REQUEST["search"]["value"]);
    $response = array();
    
    foreach ($customers as $c) {
        array_push($response, [
            "<div class='cursor-pointer text-decoration-underline'><a href='Edit.php?Id=" . $c->id . "'>" . $c->name . "</a></div>",
            $c->documentNumber,
            $c->getCity()->getCountry()->getName(),
            $c->getCity()->getName(),
            $c->address,
            $c->phoneNumber,
            $c->email
        ]);
    }
    
    $service->setResponse(
        json_encode([
            "draw" => intval($service->getParameter("draw")->getValue()),
            "recordsTotal" => intval(InventoryCustomerDAO::getRecordsTotal()),
            "recordsFiltered" => intval(InventoryCustomerDAO::getRecordsFiltered($_REQUEST["search"]["value"])),
            "data" => $response
        ])
    );
});
$service->publish();
