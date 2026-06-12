<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;
use Cloud\Engine\PHP\MySQL\Helpers;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Id", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Amount", 18, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdPaymentMethod", 36, true));
$service->setCallback(function() use ($service) {
    if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() != "Administrador"
            && $service->getParameter("Amount")->getValue() <= 0) {
        $service->setException("El monto ingresado debe ser mayor a cero.");
        exit();
    }

    $invoice = InventoryInvoiceDAO::getById($service->getParameter("Id")->getValue());
    if ($service->getParameter("Amount")->getValue() > $invoice->getPendingPayment()) {
        $service->setException("No puede ingresar un monto mayor al monto pendiente.");
    } else { 
        try {
            InventoryInvoicePaymentDAO::create(
                $service->getParameter("Amount")->getValue(),
                $service->getParameter("IdPaymentMethod")->getValue(),
                $service->getParameter("Id")->getValue(),
                CloudEngineSession::getSessionObject()->getObject()->getNames(),
                $service->getParameter("Date")->getValue()
            );
            $service->setResponse("Registro almacenado correctamente.");
        } catch (Exception $ex) {
            $service->setException($ex->getMessage());
        }
    }
});
$service->publish();