<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdCustomer", 36, true));
$service->setCallback(function() use ($service) {
    // ADAPTER -> microservicio nuevo (DELETE /api/customers/{id}). Frontend sin cambios.
    $id = $service->getParameter("IdCustomer")->getValue();
    $res = MswApiClient::request("DELETE", "/api/customers/" . rawurlencode($id));
    if (MswApiClient::isOk($res)) {
        $service->setResponse("Cliente eliminado correctamente.");
    } else if ($res["status"] == 404) {
        $service->setException("Cliente no existe.");
    } else {
        $service->setException(MswApiClient::errorMessage($res, "No se pudo eliminar el cliente."));
    }
});
$service->publish();
