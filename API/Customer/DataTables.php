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
    $customers = CustomerDAO::getCustomersDataTables($service->getParameter("start")->getValue(),$service->getParameter("length")->getValue(),$_REQUEST["search"]["value"]);
    $response = array();
    
    foreach ($customers as $c) {
        $options = "";
        
        if ($c->isActive()) {
            $options .= "<div name='btnInactive' data-id='" . $c->getIdCustomer() . "' class='text-align-center text-decoration-underline cursor-pointer'>Desactivar</div>";
        } else {
            $options .= "<div name='btnActive' data-id='" . $c->getIdCustomer() . "' class='text-align-center text-decoration-underline cursor-pointer'>Activar</div>";
        }
        
        //$options .= "<div name='btnEdit' data-id='" . $c->getIdCustomer() . "' class='text-align-center text-decoration-underline cursor-pointer'>Ver</div>";
        $options .= "<div name='btnDelete' data-id='" . $c->getIdCustomer() . "' class='text-align-center text-decoration-underline cursor-pointer'>Eliminar</div>";
        
        array_push($response, [
            "<div class='text-align-center'>" . $c->getLockerNumber() . "</div>",
            "<div class='cursor-pointer text-decoration-underline' name='btnEdit' data-id='" . $c->getIdCustomer() . "'>" . $c->getNames() . "</div>",
            $c->getEmail(),
            "<div class='text-align-center'>" . $c->getCity()->getCountry()->getName() . "</div>",
            "<div class='text-align-center'>" . $c->getCity()->getName() . "</div>",
            "<div class='text-align-center padding text-size-xs text-color-white text-weight-bold border-radius " . $c->getActiveColor() . "'>" . $c->getActiveString() . "</div>",            
            $options
        ]);
    }
    
    $service->setResponse(
        json_encode([
            "draw" => intval($service->getParameter("draw")->getValue()),
            "recordsTotal" => intval(CustomerDAO::getRecordsTotal()),
            "recordsFiltered" => intval(CustomerDAO::getRecordsFiltered($_REQUEST["search"]["value"])),
            "data" => $response
        ])
    );
});
$service->publish();
