<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->setCallback(function() use ($service) {
    // ADAPTER -> microservicio nuevo (GET /api/inventory). Se remapea al shape
    // legado (nombres de campos del Model Inventory) que consume el frontend Vue.
    $res  = MswApiClient::request("GET", "/api/inventory?limit=1000");
    $rows = array();
    if (MswApiClient::isOk($res)) {
        if (isset($res["body"]["data"])) {
            $rows = $res["body"]["data"];
        } else if (is_array($res["body"])) {
            $rows = $res["body"];
        }
    }
    $items = array();
    foreach ($rows as $r) {
        array_push($items, array(
            "id"                         => $r["id"],
            "invoice"                    => isset($r["invoice"]) ? $r["invoice"] : null,
            "product"                    => $r["product"],
            "trm"                        => $r["trm"],
            "usdPrice"                   => $r["usdPrice"],
            "copPrice"                   => isset($r["copPrice"]) ? $r["copPrice"] : null,
            "internationalShippingPrice" => isset($r["intlShippingPrice"]) ? $r["intlShippingPrice"] : null,
            "nationalShippingPrice"      => isset($r["natShippingPrice"]) ? $r["natShippingPrice"] : null,
            "totalCost"                  => isset($r["totalCost"]) ? $r["totalCost"] : null,
            "salePrice"                  => $r["salePrice"],
            "utility"                    => isset($r["utility"]) ? $r["utility"] : null,
            "idInvoice"                  => isset($r["saleInvoiceId"]) ? $r["saleInvoiceId"] : null,
            "createdTimestamp"           => isset($r["createdAt"]) ? $r["createdAt"] : null,
            "fullInvoiceCode"            => isset($r["fullInvoiceCode"]) ? $r["fullInvoiceCode"] : ("INV" . (isset($r["invoice"]) ? $r["invoice"] : "")),
            "lastTracking"               => "",
            "completeTracking"           => ""
        ));
    }
    echo json_encode($items);
});
$service->publish();
