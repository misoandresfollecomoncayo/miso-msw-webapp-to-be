<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
require_once 'Helpers.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->addParameterObj(new CloudEngineWebServiceParameterText("Id", 36, true));
$service->setCallback(function() use ($service) {
    $invoice = InventoryInvoiceDAO::getById($service->getParameter("Id")->getValue());
    $customer = $invoice->getCustomer();
    $items = $invoice->getItems();
    
    $pdf = Helpers::PDFDocument($invoice->fullInvoiceCode);
    $pdf->AddPage();

    // logotype
    $pdf->setJPEGQuality(100);
    if ($invoice->sellingCompany == "Clubick") {
        $pdf->Image("../../Static/Images/clubick.jpg", 145, 15, 60, 23, "JPG");
    } else {
        $pdf->ImageSVG("../../Static/Images/logotype.svg", 155, 15, 50, 23, "", "", "", 0, true);
    }
    
    // Annulled
    /*if ($item->wasAnnulled()) {
        $pdf->SetFont('helvetica', 'B', 50);
        $pdf->SetTextColor(255,220,220);
        $pdf->SetXY(10, 10);
        $pdf->MultiCell(195, 100, "VOID", 0, 'C', false, 0, "", "", true, 0, false, true, 60, "M", false);
    }*/
    
    $pdf->SetFont('helvetica', '', 16);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(0, 10);
    
    // Company info
    if ($invoice->sellingCompany == "Clubick") {
        $table = '<br><br><table cellpadding="3">';
        $table .= '<tr>';
        $table .= '<td style="font-size:26px;font-weight:bold">Clubick</td>';
        $table .= '</tr>';
        $table .= '<tr>';
        $table .= '<td style="font-size:15px">13794 NW 4th Street, Suite 201<br/>Sunrise, FL 33325 USA<br/>+57 (313) 781-9400 / +57 (300) 776-9591<br/>info@clubick.com<br/>www.clubick.com</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $pdf->writeHTML($table);
    } else {
        $table = '<br><br><table cellpadding="3">';
        $table .= '<tr>';
        $table .= '<td style="font-size:26px;font-weight:bold">Uniexpress Solutions</td>';
        $table .= '</tr>';
        $table .= '<tr>';
        $table .= '<td style="font-size:15px">13794 NW 4th Street, Suite 201<br/>Sunrise, FL 33325 USA<br/>+1 (954) 812-8778 / +1 (954) 706-4110<br/>info@uniexpresssolutions.com<br/>www.uniexpresssolutions.com</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $pdf->writeHTML($table);
    }
    
    // Division line
    $pdf->Line(10, 57, 206, 57, array('width' => 0.5, 'color' => array(200, 200, 200)));
    
    // Customer info
    $table = '<br/><table cellpadding="0" style="width:100%">';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; font-weight:bold; width: 70%; padding:0">CLIENTE</td>';
    $table .= '<td style="font-size:14px; font-weight:bold; width: 30%; text-align:right; padding:0">FACTURA: <span style="font-size:22px; color: red">' . $invoice->fullInvoiceCode . '</span></td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; padding:0"><b>Nombres: </b>' . $customer->documentNumber . " - " . $customer->name. '</td>';
    $table .= '<td style="font-size:14px;"></td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; padding:0"><b>Dirección: </b>' . $customer->address . '</td>';
    $table .= '<td style="padding:0; font-size: 14px; text-align: right"><b>Fecha venta: </b> ' . substr($invoice->createdTimestamp,0,10) . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; padding:0"><b>Teléfono: </b>' . $customer->phoneNumber . '</td>';
    $table .= '<td style="padding:0; font-size: 14px"></td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; padding:0px"><b>País y ciudad: </b>' . $customer->getCity()->getCountry()->getName() . " - " . $customer->getCity()->getName() . '</td>';
    $table .= '<td style="padding:0; font-size: 14px"></td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    // Items
    $table = '<table border="0" cellpadding="3">';
    $table .= '<tr>';
    $table .= '<td colspan="4" style="font-weight:bold; text-align:center; font-size:14px; background-color:gray; color:white">PRODUCTO</td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px; background-color:gray; color:white"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px; background-color:gray; color:white"></td>';
    $table .= '<td colspan="2" style="font-weight:bold; text-align:center; font-size:14px; background-color:gray; color:white">PRECIO</td>';
    $table .= '</tr>';
    
    foreach($items as $item) {
        $table .= '<tr>';
        $table .= '<td colspan="4" style="font-size:14px">' . $item->fullInvoiceCode . " - " . $item->product . '</td>';
        $table .= '<td style="text-align:center; font-size:14px">' . "" . '</td>';
        $table .= '<td style="text-align:center; font-size:14px">' . "" . '</td>';
        $table .= '<td colspan="2" style="text-align:center; font-size:14px">$ ' . number_format($item->salePrice, 2) . '</td>';
        $table .= '</tr>';
    }
    
    $table .= '<tr>';
    $table .= '<td colspan="4" style="font-weight:bold; text-align:center; font-size:14px; border-top:2px solid rgb(200,200,200)"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px; border-top:2px solid rgb(200,200,200)"></td>';
    $table .= '<td style="font-weight:bold; text-align:right; font-size:18px; border-top:2px solid rgb(200,200,200)">TOTAL:</td>';
    $table .= '<td colspan="2" style="font-weight:bold; text-align:center; font-size:18px; border-top:2px solid rgb(200,200,200)">$ ' . number_format($invoice->getTotal(), 2) . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td colspan="4" style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:right; font-size:14px">PAGADO:</td>';
    $table .= '<td colspan="2" style="font-weight:bold; text-align:center; font-size:14px">$ ' . number_format($invoice->getPaid(), 2) . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td colspan="4" style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:right; font-size:14px">PENDIENTE:</td>';
    $table .= '<td colspan="2" style="font-weight:bold; text-align:center; font-size:14px">$ ' . number_format($invoice->getPendingPayment(), 2) . '</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    $table = '<br><br><table nobr="true" cellpadding="3">';
    $table .= '<tr>';
    
    if ($invoice->sellingCompany == "Clubick") {
        $table .= '<td style="font-size:14px; text-align:center; font-weight:bold">¡Somos clubick.com, la forma más fácil, rápida y segura de comprar por Internet!</td>';
    } else {
        $table .= '<td style="font-size:14px; text-align:center; font-weight:bold">GRACIAS POR TU COMPRA</td>';
    }
    
    
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->setOpenCell(true);
    $pdf->writeHTML($table);
    
    ob_clean();
    $pdf->Output($invoice->fullInvoiceCode . '.pdf', 'I');
});
$service->publish();
