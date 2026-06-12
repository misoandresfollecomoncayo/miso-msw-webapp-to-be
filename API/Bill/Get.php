<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("StartDate", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("EndDate", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Country", 36, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Company", 36, false));
$service->setCallback(function() use ($service) {
    $bills = BillDAO::getTracking($service->getParameter("StartDate")->getValue(), $service->getParameter("EndDate")->getValue(), $service->getParameter("Country")->getValue(), $service->getParameter("Company")->getValue());
    $shipments = ShippingDAO::getTracking($service->getParameter("StartDate")->getValue(), $service->getParameter("EndDate")->getValue(), $service->getParameter("Country")->getValue(), $service->getParameter("Company")->getValue());
    $return = array();
    
    foreach ($bills as $b) {
        $customer = $b->getCustomer();
        
        $boxes = array();
        
        foreach ($b->getItems() as $box) {
            $tracking = $box->getTracking();
            $nBox = [
                'id' => $box->getIdBillItem(),
                'boxNumber' => $box->getBoxNumber(),
                'trackingDate' => date("Y-m-d"),
                'lastTracking' => count($tracking) > 0 ? $tracking[0]->getDescription() : "",
                'initialLastTracking' => count($tracking) > 0 ? $tracking[0]->getDescription() : "",
                'delivered' => $box->wasDelivered()
            ];
            array_push($boxes,$nBox);
        }
        
        $n = [
            'type' => "bill",
            'id' => $b->getIdBill(),
            'systemDate' => $b->getCreatedTimestamp(),
            'date' => $b->getCreatedTimestampHuman(),
            'billNumber' => $b->getBillNumber(),
            'boxes' => $boxes,
            'customerName' => $customer != null ? $customer->getNames() : $b->getFrom(),
            'lockerNumber' => $customer != null ? $customer->getLockerNumber() : ""
        ];
        array_push($return, $n);
    }
    
    foreach ($shipments as $s) {
        $purchases = $s->getPurchases();
        $customer = count($purchases) > 0 ? $purchases[0]->getCustomer() : null;
        $tracking = $s->getTracking();
        
        $n = [
            "type" => "shipment",
            'id' => $s->getIdShipping(),
            'systemDate' => $s->getCreatedTimestamp(),
            'date' => $s->getCreatedTimestampHuman(),
            'billNumber' => $s->getShippingNumber(),
            'sequenceNumber' => $s->getSequenceNumber(),
            'customerName' => $customer != null ? $customer->getNames() : "",
            'lockerNumber' => $customer != null ? $customer->getLockerNumber() : "",
            'trackingDate' => date("Y-m-d"),
            'lastTracking' => count($tracking) > 0 ? $tracking[0]->getDescription() : "",
            'initialLastTracking' => count($tracking) > 0 ? $tracking[0]->getDescription() : "",
            'delivered' => $s->wasDelivered()
        ];
        array_push($return, $n);
    }
    
    usort($return, "sortFunction");
    
    echo json_encode($return);
});
$service->publish();

function sortFunction( $a, $b ) {
    return (strtotime($a["systemDate"]) < strtotime($b["systemDate"])) ? -1 : 1;
}