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
    $purchases = PurchaseDAO::getPurchasesDataTables($service->getParameter("start")->getValue(),$service->getParameter("length")->getValue(),$_REQUEST["search"]["value"]);
    $response = array();
    
    foreach ($purchases as $p) {
        array_push($response, [
            "<div class='text-align-center'>" . $p->getCustomer()->getLockerNumber() . "</div>",
            "<div class='text-align-center'>" . $p->getCustomer()->getNames() . "</div>",
            "<div class='text-align-center'>" . $p->getTrackingNumber() . "</div>",
            "<div style='display:flex; overflow:hidden;max-width:110px;white-space:nowrap' data-status='closed'><button name='btnShowBoxes' class='margin-right'>+</button>" . $p->getContent() . "</div>",
            "<div class='text-align-center'>" . $p->getQuantity() . "</div>",
            "<div class='text-align-center'>" . $p->getNetWeight() . "</div>",
            "<div class='border-radius padding text-size-xs text-weight-bold text-color-white text-align-center " . $p->getStatusColor() . "'>" . $p->getStatus() . "</div>",
            "<div class='text-align-center'>" . $p->getCreatedTimestampFormatted() . "</div>",
            "<div name='btnEdit' data-id='" . $p->getIdPurchase() . "' class='text-align-center text-decoration-underline cursor-pointer'>Editar</div>",
            "<div name='btnDelete' data-id='" . $p->getIdPurchase() . "' class='text-align-center text-decoration-underline cursor-pointer'>Eliminar</div>"
        ]);
    }
    
    $service->setResponse(
        json_encode([
            "draw" => intval($service->getParameter("draw")->getValue()),
            "recordsTotal" => intval(PurchaseDAO::getRecordsTotal()),
            "recordsFiltered" => intval(PurchaseDAO::getRecordsFiltered($_REQUEST["search"]["value"])),
            "data" => $response
        ])
    );
});
$service->publish();
