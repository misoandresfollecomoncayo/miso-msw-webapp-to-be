<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
require_once '../../Libs/PHPExcel/PHPExcel.php';

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
    $sessionUser = CloudEngineSession::getSessionObject();

    $report = ReportDAO::getReportById($service->getParameter("IdReport")->getValue());
    
    if ($report != null) {
        $objects = array();
        
        $connection = new Connection();

        $result = null;

        if ($service->getParameter("IdReport")->getValue() == 11
            && $sessionUser->getObject()->getRole()->getName() != "Administrador") {
            $result = CloudEngineMySQLQuery::execute($connection, "SELECT invoiceDate, invoice, customer, lastTracking, paymentDate, paymentAmount, paymentMethod, company FROM VW_Report_InventoryPayments WHERE (paymentDate BETWEEN ? AND ?) AND company = ?", json_decode($service->getParameter("Filters")->getValue()));
        } else if ($service->getParameter("IdReport")->getValue() == 10
            && $sessionUser->getObject()->getRole()->getName() != "Administrador") {
            $result = CloudEngineMySQLQuery::execute($connection, "SELECT date, inv, product FROM VW_Report_Inventory WHERE DATE(`date`) BETWEEN ? AND ?;", json_decode($service->getParameter("Filters")->getValue()));
        } else if ($service->getParameter("IdReport")->getValue() == 9
            && $sessionUser->getObject()->getRole()->getName() != "Administrador") {
            $result = CloudEngineMySQLQuery::execute($connection, "SELECT date, invoice, customer, product, salePrice FROM VW_Report_InventorySales WHERE `sellingCompany` = ? AND DATE(`date`) BETWEEN ? AND ?;", json_decode($service->getParameter("Filters")->getValue()));
        } else {
            $result = CloudEngineMySQLQuery::execute($connection, $report->getQuery(), json_decode($service->getParameter("Filters")->getValue()));
        }
                
        while ($row = $result->fetch_assoc()) {
            array_push($objects, new CloudEngineMySQLObject($row));
        }

        if (count($objects) == 0) { echo "No existen resultados para la búsqueda."; exit; }
    
        ob_clean();     // Clean output buffer

        $PHPExcel = new PHPExcel();
        $PHPExcel->getProperties()
        ->setCreator("Uniexpress Solutions Inc.")
        ->setTitle($report->getName());
        $PHPExcel->setActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->getStyle("A1:AAA1")->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->getStyle("A1:AAA1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $columns = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $currentRow = 1;
        
        // Adjust columns sizes
        $currentColumn = 0;
        $additional = -1;
        for ($i=0; $i< count($objects[0]->attributes); $i++) {
            $col = '';
            if ($currentColumn == strlen($columns)) {
                $currentColumn = 0;
                $additional ++;
            }
            if ($additional > -1) {
                $col .= $columns[$additional];
            }
            $col .= $columns[$currentColumn];

            $PHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);

            $currentColumn ++;
        }

        // Set titles
        $currentColumn = 0;
        $additional = -1;
        $keys = array_keys($objects[0]->attributes);
        foreach ($keys as $key) {
            $col = '';
            if ($currentColumn == strlen($columns)) {
                $currentColumn = 0;
                $additional ++;
            }
            if ($additional > -1) {
                $col .= $columns[$additional];
            }
            $col .= $columns[$currentColumn];

            $PHPExcel->setActiveSheetIndex(0)->setCellValue($col . $currentRow, $key);

            $currentColumn ++;
        }
        $currentRow ++;

        // Contenido
        foreach ($objects as $object) {
            $currentColumn = 0;
            $additional = -1;
            foreach ($object->attributes as $propertie) {
                $col = '';
                if ($currentColumn == strlen($columns)) {
                    $currentColumn = 0;
                    $additional ++;
                }
                if ($additional > -1) {
                    $col .= $columns[$additional];
                }
                $col .= $columns[$currentColumn];

                $type = PHPExcel_Cell_DataType::TYPE_STRING;

                if (is_numeric($propertie)) {
                    $type = PHPExcel_Cell_DataType::TYPE_NUMERIC;
                }

                $PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($col . $currentRow, $propertie, $type);
                $currentColumn ++;
            }
            $currentRow ++;
        }

        $PHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $report->getName() . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit();
    } else {
        $service->setException("Reporte no válido.");
    }
});
$service->publish();
