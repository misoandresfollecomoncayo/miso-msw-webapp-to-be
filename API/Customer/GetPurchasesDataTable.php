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
$service->addParameterObj(new CloudEngineWebServiceParameterText("idCustomer", 36, true));
/*$service->addParameterObj(new CloudEngineWebServiceParameterText("country", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("order", 100, true));*/
$service->setCallback(function() use ($service) {
    $purchases = PurchaseDAO::getCustomerPurchasesDataTables($service->getParameter("idCustomer")->getValue(), $service->getParameter("start")->getValue(),$service->getParameter("length")->getValue(),$_REQUEST["search"]["value"]);
    $response = array();
    
    foreach ($purchases as $p) {
        $customer = $p->getCustomer();
        
        /*$code = "<tr>";
            $code .= "<td class='text-align-center'>" . $p->getCustomer()->getLockerNumber() . "</td>";
            $code .= "<td class='text-align-center'>" . $p->getTrackingNumber() . "</td>";
            $code .= "<td>" . $p->getContent() . "</td>";
            $code .= "<td>" . $p->getNetWeight() . "</td>";
            $code .= "<td class='text-align-center'><div class='border-radius text-size-xs padding text-weight-bold text-color-white " . $p->getStatusColor() . "'>" . $p->getStatus() . "</div></td>";
            $code .= "<td class='text-align-center'>" . $p->getCreatedTimestampFormatted() . "</td>";
            $code .= "<td><div name='btnDetail' data-id='" . $p->getIdPurchase() . "' class='text-decoration-underline cursor-pointer'>Editar</div></td>";
            $code .= "</tr>";
            echo $code;
         */
        
        array_push($response, [
            $paymentCheck,
            "<div class='text-align-center'>" . $p->getCustomer()->getLockerNumber() . "</div>",
            "<div class='text-align-center'>" . $p->getTrackingNumber() . "</div>",
            "<div class=''>" . $p->getContent() . "</div>",
            "<div class=''>" . $p->getNetWeight() . "</div>",
            "<div class='text-align-center'><div class='border-radius text-size-xs padding text-weight-bold text-color-white " . $p->getStatusColor() . "'>" . $p->getStatus() . "</div></div>",
            "<div class='text-align-center'>" . $p->getCreatedTimestampFormatted() . "</div>",
            "<div><div name='btnDetail' data-id='" . $p->getIdPurchase() . "' class='text-decoration-underline cursor-pointer'>Editar</div></div>"
        ]);
    }
    
    $service->setResponse(
        json_encode([
            "draw" => intval($service->getParameter("draw")->getValue()),
            "recordsTotal" => intval(PurchaseDAO::getRecordsTotal()),
            "recordsFiltered" => intval($_REQUEST["search"]["value"] == null && $_REQUEST["search"]["value"] != "" ? PurchaseDAO::getRecordsTotal() : PurchaseDAO::getRecordsFiltered($_REQUEST["search"]["value"])),
            "data" => $response
        ])
    );
});
$service->publish();
