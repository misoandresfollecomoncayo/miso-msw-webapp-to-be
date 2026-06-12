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
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("company", 11, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("country", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("order", 100, true));
$service->setCallback(function() use ($service) {
    //echo $service->getParameter("company")->getValue();

    $bills = BillDAO::getBillsDataTables($service->getParameter("start")->getValue(),$service->getParameter("length")->getValue(),$_REQUEST["search"]["value"],$service->getParameter("company")->getValue(),$service->getParameter("country")->getValue(),$service->getParameter("order")->getValue());
    $response = array();
    
    foreach ($bills as $b) {
        $customer = $b->getCustomer();
        $shipmentCompany = $b->getShipmentCompany();
        
        $paymentCheck = $b->getPaymentStatus() == "PARCIALES" || $b->getPaymentStatus() == "SIN PAGAR" ? "<div class='text-align-center'><input name='chkPay' data-id='" . $b->getIdBill() . "' type='checkbox' /></div>" :  "" ;
        
        array_push($response, [
            $paymentCheck,
            "<div class='text-align-center'>" . substr($b->getCreatedTimestamp(), 0, 10) . "</div>",
            "<div class='text-align-center'>" . $b->getBillNumber() . "</div>",
            "<div class='text-align-center'>" . ($customer != null ? $customer->getLockerNumber() : "N/A") . "</div>",
            "<div class='text-align-center'>" . $b->getFrom() . "</div>",
            "<div class='text-align-center'>" . $b->getTo() . "</div>",
            "<div class='text-align-center'>$ " . number_format($b->getTotal(),2) . " " . $b->getCurrency() . "</div>",
            "<div class='text-align-center' style='display:flex; overflow:hidden;max-width:110px;white-space:nowrap' data-status='closed'><button name='btnShowBoxes' class='margin-right'>+</button>" . $b->getBoxesConcatenated() . "</div>",
            "<div class='text-align-center'>" . ($shipmentCompany != null ? $shipmentCompany->getName() : "") . "</div>",
            "<div class='text-align-center'><div class='padding border-radius text-color-white text-weight-bold " . $b->getPaymentColor() . " text-size-xs display-inline-block'>" . $b->getPaymentStatus() . "</div></div>",
            //"<div name='btnView' data-id='" . $b->getIdBill() . "' class='text-align-center text-decoration-underline cursor-pointer'>Ver</div>",
            "<button id='" . $b->getIdBill() . "' data-status='" . $b->getPaymentStatus() . "' name='btnContextMenu'><i class='fa fa-ellipsis-h'></i></button></td>"
            /*!$b->wasAnnulled() ? "<div name='btnEdit' data-id='" . $b->getIdBill() . "' class='text-align-center text-decoration-underline cursor-pointer'>Editar</div>" : "<div></div>",
            !$b->wasAnnulled() ? "<div name='btnPay' data-id='" . $b->getIdBill() . "' class='text-align-center text-decoration-underline cursor-pointer'>Pagos</div>" : "<div></div>",
            !$b->wasAnnulled() ? "<div name='btnTracking' data-id='" . $b->getIdBill() . "' class='text-align-center text-decoration-underline cursor-pointer'>Trazabilidad</div>" : "<div></div>",
            !$b->wasAnnulled() ? "<div name='btnAnnull' data-id='" . $b->getIdBill() . "' class='text-align-center text-decoration-underline cursor-pointer'>Anular</div>" : "<div></div>"*/
        ]);
    }
    
    $service->setResponse(
        json_encode([
            "draw" => intval($service->getParameter("draw")->getValue()),
            "recordsTotal" => intval(BillDAO::getRecordsTotal()),
            "recordsFiltered" => intval($_REQUEST["search"]["value"] == null && $_REQUEST["search"]["value"] != "" ? BillDAO::getRecordsTotal() : BillDAO::getRecordsFiltered($_REQUEST["search"]["value"], $service->getParameter("company")->getValue(), $service->getParameter("country")->getValue())),
            "data" => $response
        ])
    );
});
$service->publish();
