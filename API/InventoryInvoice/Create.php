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
$service->addParameterObj(new CloudEngineWebServiceParameterText("ProductsIds", 36000, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("SellingCompany", 100, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdInventoryCustomer", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("AmountPaid", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdPaymentMethod", 36, false));
$service->setCallback(function() use ($service) {
    try {
        $idInvoice = InventoryInvoiceDAO::create(
            $service->getParameter("SellingCompany")->getValue(),
            $service->getParameter("IdInventoryCustomer")->getValue()
        );
        
        InventoryInvoicePaymentDAO::create(
            $service->getParameter("AmountPaid")->getValue(),
            $service->getParameter("IdPaymentMethod")->getValue(),
            $idInvoice,
            CloudEngineSession::getSessionObject()->getObject()->getNames(),
            $service->getParameter("Date")->getValue(),
        );
        
        $productsIds = explode(",", $service->getParameter("ProductsIds")->getValue());
        foreach ($productsIds as $id) {
            if ($id != "") {
                InventoryDAO::sell($id, $idInvoice);
            }
        }
        
        $service->setResponse($idInvoice);
    } catch (Exception $ex) {
        $service->setException($ex->getMessage());
    }
});
$service->publish();