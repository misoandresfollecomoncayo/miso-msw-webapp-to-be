<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->addParameterObj(new CloudEngineWebServiceParameterText("Purchases", 10000, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("TRM", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("PoundValue", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("VolumetricPoundValue", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("DeclaredValue", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Secure", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("AdditionalValue", 10, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("AdditionalDescription", 1000, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Currency", 3, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("PaymentMethod", 36, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("SequenceNumber", 100, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdShipmentCompany", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, true));
$service->setCallback(function() use ($service) {
    $purchases = json_decode($service->getParameter("Purchases")->getValue());
    $TRM = $service->getParameter("TRM")->getValue();
    $poundValue = $service->getParameter("PoundValue")->getValue();
    $volumetricPoundValue = $service->getParameter("VolumetricPoundValue")->getValue();
    $declaredValue = $service->getParameter("DeclaredValue")->getValue();
    $secure = $service->getParameter("Secure")->getValue();
    $additionalValue = $service->getParameter("AdditionalValue")->getValue();
    $additionalDescription = $service->getParameter("AdditionalDescription")->getValue();
    $currency = $service->getParameter("Currency")->getValue();
    $paymentMethod = $service->getParameter("PaymentMethod")->getValue();
    $sequenceNumber = $service->getParameter("SequenceNumber")->getValue();
    $idShipmentCompany = $service->getParameter("IdShipmentCompany")->getValue();
    $date = $service->getParameter("Date")->getValue();
    
    if (count($purchases) > 0) {
        if (is_numeric($TRM) && is_numeric($poundValue) && is_numeric($volumetricPoundValue) && is_numeric($declaredValue) && is_numeric($secure) && is_numeric($additionalValue)) {
            $netWeight = 0;
            $volumetricWeight = 0;
            $tax = 0;
            $freight = 0;
            
            $purchasesObjs = array();
            
            foreach ($purchases as $p) {
                array_push($purchasesObjs, PurchaseDAO::getPurchaseById($p));
            }
            
            foreach ($purchasesObjs as $p) {
                $netWeight += $p->getNetWeight();
                $volumetricWeight += $p->getVolumetricWeight();
            }
            
            // Tax
            if ($purchasesObjs[0]->getCustomer()->getCity()->getCountry()->getName() == "Colombia") {
                if (($currency == "USD" && $declaredValue < 201)
                        || ($currency == "COP" && $declaredValue < (201 * $TRM))){
                    $tax = round($declaredValue * 0.12, 2);
                } else {
                    $tax = round($declaredValue * 0.31, 2);
                }
            }
            
            // Freight
            if ($volumetricWeight < $netWeight) {
                $freight = round($poundValue * $netWeight, 2);
            } else {
                $freight = round(($poundValue * $netWeight) + ($volumetricPoundValue * $volumetricWeight), 2);
            }
            
            // Total
            $total = round($tax + $freight + $secure + $additionalValue, 2);
            
            $uuid = ShippingDAO::create(
                $netWeight,
                $volumetricWeight,
                $TRM,
                $poundValue,
                $volumetricPoundValue,
                $declaredValue,
                $tax,
                $freight,
                $secure,
                $additionalValue,
                $additionalDescription,
                $total,
                $currency,
                $paymentMethod,
                $sequenceNumber,
                $idShipmentCompany,
                $date);
            
            foreach ($purchasesObjs as $p) {
                PurchaseDAO::setShipping($p->getIdPurchase(), $uuid, CloudEngineSession::getSessionObject()->getIdRegister());
            }
            
            $service->setResponse($uuid);
        } else {
            $service->setException("TRM, Valor libra, valor libra volumétrica, valor declarado, seguro y valor adicional, deben ser números.");
        }
    } else {
        $service->setException("Debe agregar mínimo 1 item.");
    }
});
$service->publish();
