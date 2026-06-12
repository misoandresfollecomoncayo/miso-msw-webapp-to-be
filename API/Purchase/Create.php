<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;

use Cloud\Engine\PHP\HTTP\CloudEngineSession;

use Cloud\Engine\PHP\MySQL\Helpers;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Date", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("LockerNumber", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("TrackingNumber", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Content", 2000, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdStore", 36, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("NetWeight", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("Long", 11, false));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("Width", 11, false));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("High", 11, false));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("Quantity", 11, true));
$service->setCallback(function() use ($service) {
    $customer = CustomerDAO::getCustomerByLocker($service->getParameter("LockerNumber")->getValue());
    $trackingNumber = PurchaseDAO::getPurchaseByTrackingNumber($service->getParameter("TrackingNumber")->getValue());
    
    if ($customer != null) {
        $purchaseId = PurchaseDAO::create(
            $service->getParameter("Content")->getValue(),
            $service->getParameter("NetWeight")->getValue(),
            $service->getParameter("Long")->getValue(),
            $service->getParameter("Width")->getValue(),
            $service->getParameter("High")->getValue(),
            $customer->getIdCustomer(),
            $service->getParameter("TrackingNumber")->getValue(),
            $service->getParameter("IdStore")->getValue(),
            $service->getParameter("Quantity")->getValue(),
            CloudEngineSession::getSessionObject()->getObject()->getIdSystemUser(),
            $service->getParameter("Date")->getValue()
        );

        // Save picture 1 (if is set)
        $picture1 = isset($_FILES["Picture1"]) ? $_FILES["Picture1"] : null;
        if ($picture1 != null) {
            $picture1UUID = Helpers::UUID();
            move_uploaded_file($picture1["tmp_name"], PRIVATE_PATH_UPLOADS . $picture1UUID);
            PurchasePictureDAO::create($picture1UUID, $purchaseId);
        }

        // Save picture 2 (if is set)
        $picture2 = isset($_FILES["Picture2"]) ? $_FILES["Picture2"] : null;
        if ($picture2 != null) {
            $picture2UUID = Helpers::UUID();
            move_uploaded_file($picture2["tmp_name"], PRIVATE_PATH_UPLOADS . $picture2UUID);
            PurchasePictureDAO::create($picture2UUID, $purchaseId);
        }

        // Save picture 3 (if is set)
        $picture3 = isset($_FILES["Picture3"]) ? $_FILES["Picture3"] : null;
        if ($picture3 != null) {
            $picture3UUID = Helpers::UUID();
            move_uploaded_file($picture3["tmp_name"], PRIVATE_PATH_UPLOADS . $picture3UUID);
            PurchasePictureDAO::create($picture3UUID, $purchaseId);
        }

        // Update purchase alert status
        ArrivalAlertDAO::updateStatusByTrackingNumber(trim($service->getParameter("TrackingNumber")->getValue()), ArrivalAlert::STATUS_RECEIVED);

        $service->setResponse("Compra registrada correctamente al casillero No. " . $service->getParameter("LockerNumber")->getValue());
    } else {
        $service->setException("Casillero No. " . $service->getParameter("LockerNumber")->getValue() . " no existe.");
    }
});
$service->publish();
