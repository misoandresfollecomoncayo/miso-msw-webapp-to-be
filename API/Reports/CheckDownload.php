<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\HTTP\CloudEngineSession;

use Cloud\Engine\PHP\MySQL\CloudEngineMySQLQuery;
use Cloud\Engine\PHP\MySQL\CloudEngineMySQLObject;

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterInteger;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;

$service = new CloudEngineWebService();
$service->setMethod(CloudEngineWebService::METHOD_REQUEST);
$service->addParameterObj(new CloudEngineWebServiceParameterInteger("IdReport", 11, true));
$service->addParameterObj(new CloudEngineWebServiceParameterText("Filters", 5000, false));
$service->setCallback(function() use ($service) {
    $report = ReportDAO::getReportById($service->getParameter("IdReport")->getValue());
    
    if ($report != null) {
        $objects = array();
        
        $connection = new Connection();
        $result = CloudEngineMySQLQuery::execute($connection, $report->getQuery(), json_decode($service->getParameter("Filters")->getValue()));
        while ($row = $result->fetch_assoc()) {
            array_push($objects, new CloudEngineMySQLObject($row));
        }
        
        if (count($objects) == 0) {
            $service->setException("No existen resultados para la búsqueda.");
        } else {
            $service->setResponse("Descargando " . count($objects) . " registros.");
        }
    } else {
        $service->setException("Reporte no válido.");
    }
});
$service->publish();
