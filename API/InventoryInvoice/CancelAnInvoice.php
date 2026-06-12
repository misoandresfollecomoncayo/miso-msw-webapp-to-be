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
$service->addParameterObj(new CloudEngineWebServiceParameterText("InvoiceId", 36, true));
$service->setCallback(function() use ($service) {
    try {
        $products = InventoryDAO::getByIdInvoice($service->getParameter("InvoiceId")->getValue());
        foreach($products as $p) {
            InventoryDAO::restoreToInventory($p->id);
        }

        InventoryInvoiceTrackingDAO::create(
            date("Y-m-d"),
            "Venta anulada",
            $service->getParameter("InvoiceId")->getValue(),
            CloudEngineSession::getSessionObject()->getObject()->getNames()
        );
        InventoryInvoiceDAO::void($service->getParameter("InvoiceId")->getValue());
        $service->setResponse("Invoice canceled.");
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();