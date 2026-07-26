<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->setType(CloudEngineWebService::TYPE_RAW);
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("draw", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("start", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("length", 11, true));
$service->setCallback(function() use ($service) {
    // ADAPTER -> microservicio nuevo (GET /api/customers?page=&limit=&search=).
    // Se reconstruye EXACTAMENTE el formato server-side de DataTables (HTML embebido)
    // que espera el frontend. Los data-id ahora son los UUID de la API nueva, con lo
    // que Editar/Eliminar/Activar/Desactivar operan de forma consistente contra ella.
    $draw   = intval($service->getParameter("draw")->getValue());
    $start  = intval($service->getParameter("start")->getValue());
    $length = intval($service->getParameter("length")->getValue());
    $search = isset($_REQUEST["search"]["value"]) ? $_REQUEST["search"]["value"] : "";
    $page   = ($length > 0) ? (intval($start / $length) + 1) : 1;

    $res  = MswApiClient::request("GET", "/api/customers?page=" . $page . "&limit=" . $length . "&search=" . rawurlencode($search));
    $rows = (MswApiClient::isOk($res) && isset($res["body"]["data"])) ? $res["body"]["data"] : array();
    $total = (MswApiClient::isOk($res) && isset($res["body"]["meta"]["total"])) ? intval($res["body"]["meta"]["total"]) : count($rows);

    $data = array();
    foreach ($rows as $c) {
        $loc          = MswApiClient::resolveCountryCity($c["cityId"]);
        $isActive     = !empty($c["active"]);
        $activeColor  = $isActive ? "background-color-green" : "background-color-red";
        $activeString = $isActive ? "ACTIVO" : "INACTIVO";
        $id           = $c["id"];

        $options = "";
        if ($isActive) {
            $options .= "<div name='btnInactive' data-id='" . $id . "' class='text-align-center text-decoration-underline cursor-pointer'>Desactivar</div>";
        } else {
            $options .= "<div name='btnActive' data-id='" . $id . "' class='text-align-center text-decoration-underline cursor-pointer'>Activar</div>";
        }
        $options .= "<div name='btnDelete' data-id='" . $id . "' class='text-align-center text-decoration-underline cursor-pointer'>Eliminar</div>";

        array_push($data, array(
            "<div class='text-align-center'>" . $c["lockerNumber"] . "</div>",
            "<div class='cursor-pointer text-decoration-underline' name='btnEdit' data-id='" . $id . "'>" . $c["names"] . "</div>",
            $c["email"],
            "<div class='text-align-center'>" . $loc["country"] . "</div>",
            "<div class='text-align-center'>" . $loc["city"] . "</div>",
            "<div class='text-align-center padding text-size-xs text-color-white text-weight-bold border-radius " . $activeColor . "'>" . $activeString . "</div>",
            $options
        ));
    }

    $service->setResponse(
        json_encode(array(
            "draw"            => $draw,
            "recordsTotal"    => $total,
            "recordsFiltered" => $total,
            "data"            => $data
        ))
    );
});
$service->publish();
