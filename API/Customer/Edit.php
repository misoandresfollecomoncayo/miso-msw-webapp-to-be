<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterDate;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterEmail;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterBoolean;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdCustomer", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Names", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Gender", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterDate("Birthdate", true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Language", 10, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdDocumentType", 36, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("DocumentNumber", 100, false));
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdCity", 36, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Address", 200, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Telephone", 45, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Telephone2", 45, false));
$service->addParameterObj(new CloudEngineWebServiceParameterEmail("Email", true));
$service->setCallback(function() use ($service) {
    // ADAPTER -> microservicio nuevo (PATCH /api/customers/{id}). Frontend sin cambios.
    $id = $service->getParameter("IdCustomer")->getValue();
    $body = array(
        "names"          => $service->getParameter("Names")->getValue(),
        "gender"         => MswApiClient::genderToApi($service->getParameter("Gender")->getValue()),
        "birthdate"      => MswApiClient::toIsoDate($service->getParameter("Birthdate")->getValue()),
        "language"       => MswApiClient::languageToApi($service->getParameter("Language")->getValue()),
        "documentType"   => MswApiClient::documentTypeToApi($service->getParameter("IdDocumentType")->getValue()),
        "documentNumber" => $service->getParameter("DocumentNumber")->getValue(),
        "cityId"         => MswApiClient::cityIdToApi($service->getParameter("IdCity")->getValue()),
        "address"        => $service->getParameter("Address")->getValue(),
        "telephone"      => $service->getParameter("Telephone")->getValue(),
        "email"          => $service->getParameter("Email")->getValue()
    );
    $telephone2 = $service->getParameter("Telephone2")->getValue();
    if (!empty($telephone2)) {
        $body["telephone2"] = $telephone2;
    }

    $res = MswApiClient::request("PATCH", "/api/customers/" . rawurlencode($id), $body);
    if (MswApiClient::isOk($res)) {
        $service->setResponse("Cliente actualizado correctamente.");
    } else if ($res["status"] == 404) {
        $service->setException("Cliente no existe.");
    } else {
        $service->setException(MswApiClient::errorMessage($res, "No se pudo actualizar el cliente."));
    }
});
$service->publish();
