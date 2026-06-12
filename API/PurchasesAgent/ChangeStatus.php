<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterNumeric;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("Id", 36, true));
$service->setCallback(function() use ($service) {
    $item = PurchasesAgentItemDAO::getById($service->getParameter("Id")->getValue());
    if ($item->getStatus() == PurchasesAgentItem::STATUS_PAID) {
        PurchasesAgentItemDAO::changeStatus($service->getParameter("Id")->getValue(), PurchasesAgentItem::STATUS_PENDING);
        $service->setResponse("PENDING");
    } else {
        PurchasesAgentItemDAO::changeStatus($service->getParameter("Id")->getValue(), PurchasesAgentItem::STATUS_PAID);
        $service->setResponse("PAID");
    }
});
$service->publish();