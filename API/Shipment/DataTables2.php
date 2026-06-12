<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->setType(CloudEngineWebService::TYPE_RAW);
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("draw", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("start", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("length", 11, true));
$service->setCallback(function() use ($service) {
    $shipments = ShippingDAO::getShipmentsDataTables($service->getParameter("start")->getValue(),$service->getParameter("length")->getValue(),$_REQUEST["search"]["value"]);
    
    $response = array();
    
    foreach ($shipments as $s) {
        $shipmentCompany = $s->getShipmentCompany();
        $trackings = $s->getTracking();
        $tracking = count($trackings) > 0 ? $trackings[0] : "";

        $paymentCheck = $s->getPaymentStatus() == "PARCIALES" || $s->getPaymentStatus() == "SIN PAGAR" ? "<div class='text-align-center'><input name='chkPay' data-id='" . $s->getIdShipping() . "' type='checkbox' /></div>" :  "" ;
        
        array_push($response, [
            $paymentCheck,
            "<div class='text-align-center'>" . $s->getCreatedTimestamp() . "</div>",
            "<div class='text-align-center'>" . $s->getShippingNumber() . "</div>",
            "<div class='text-align-center'>" . (count($s->getPurchases()) > 0 ? $s->getPurchases()[0]->getCustomer()->getNames() : "") . "</div>",
            "<div class='text-align-center'>" . $s->getSequenceNumber() . "</div>",
            "<div class='text-align-center'>" . ($shipmentCompany != null ? $shipmentCompany->getName() : "") . "</div>",
            "<div class='text-align-center'>" . number_format($s->getTotal(),2) . " " . $s->getCurrency() . "</div>",
            "<div class='text-align-center'>" . number_format($s->getNetWeight(),2) . "</div>",
            "<div class='text-align-center'><div class='padding border-radius " . $s->getPaymentColor() . " text-color-white text-size-xs text-weight-bold display-inline-block'>" . $s->getPaymentStatus() . "</div></div>",
            "<div class='text-align-center'><input name='txtTrackingDescription_" . $s->getIdShipping() . "' class='input-text-underline' style='width:250px' value='" . ($tracking != null ? $tracking->getDescription() : "") . "' /></div>",
            "<div class='text-align-center'><input type='date' name='txtTrackingDate_" . $s->getIdShipping() . "' class='input-text-underline' style='width:120px' value='" . ($tracking != null ? substr($tracking->getCreatedTimestamp(),0,10) : "") . "' /></div>",
            "<div class='text-align-center'><button name='btnSaveTracking' data-id='" . $s->getIdShipping() . "'><i class='fa fa-save'></i></button></div>",
            "<div class='text-align-center'><input name='chkDelivered' data-id='" . $s->getIdShipping() . "' type='checkbox' " . ($s->wasDelivered() ? "checked disabled" : "") . " /></div>",
            "<div class='text-align-center'><button id='" . $s->getIdShipping() . "' data-status='" . $s->getPaymentStatus() . "' name='btnContextMenu'><i class='fa fa-ellipsis-h'></i></button></div>",
        ]);
    }
    
    $service->setResponse(
        json_encode([
            "draw" => intval($service->getParameter("draw")->getValue()),
            "recordsTotal" => intval(ShippingDAO::getRecordsTotal()),
            "recordsFiltered" => intval($_REQUEST["search"]["value"] == null && $_REQUEST["search"]["value"] != "" ? ShippingDAO::getRecordsTotal() : ShippingDAO::getRecordsFiltered($_REQUEST["search"]["value"])),
            "data" => $response
        ])
    );
});
$service->publish();
