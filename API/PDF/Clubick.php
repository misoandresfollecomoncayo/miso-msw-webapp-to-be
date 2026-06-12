<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
require_once 'Helpers.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->addParameterObj(new CloudEngineWebServiceParameterText("Id", 36, true));
$service->setCallback(function() use ($service) {
    $item = ClubickDAO::getById($service->getParameter("Id")->getValue());
    
    $pdf = Helpers::PDFDocument("Factura Clubick");
    $pdf->AddPage();

    // Uniexpress logotype
    $pdf->setJPEGQuality(100);
    $pdf->Image("../../Static/Images/clubick.jpg", 145, 15, 60, 23, "JPG");
    
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
    $table = '<br><br><table cellpadding="3">';
    $table .= '<tr>';
    $table .= '<td style="font-size:26px;font-weight:bold">Clubick</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:15px">13790 NW 4th St, Suite 107 - Sunrise - Florida 33325<br/>Calle 20 No. 38-28 Ed. Gran Avenida Of. 205 Pasto - Colombia<br/>3137819400 - 3007769591<br/>info@clubick.com<br/>www.clubick.com</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    // Division line
    $pdf->Line(10, 53, 206, 53, array('width' => 0.5, 'color' => array(200, 200, 200)));
    
    // Customer info
    $table = '<br/><table cellpadding="0" style="width:100%">';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; font-weight:bold; width: 70%; padding:0">CLIENTE</td>';
    $table .= '<td style="font-size:14px; font-weight:bold; width: 30%; text-align:right; padding:0">FACTURA <span style="font-size:22px; color: red">' . $item->invoiceNumber . '</span></td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; padding:0">' . $item->getCustomer() . '</td>';
    $table .= '<td style="font-size:14px; padding:0"></td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; padding:0">' . $item->getCustomerDocument() . '</td>';
    $table .= '<td style="padding:0; font-size: 14px"></td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; padding:0">' . $item->getCustomerAddress() . '</td>';
    $table .= '<td style="padding:0; font-size: 14px"></td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; padding:0px">' . $item->getCustomerPhone() . '</td>';
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
    
    $table .= '<tr>';
        $table .= '<td colspan="4" style="font-size:14px">' . utf8_encode($item->getProduct()) . '</td>';
        $table .= '<td style="text-align:center; font-size:14px">' . "" . '</td>';
        $table .= '<td style="text-align:center; font-size:14px">' . "" . '</td>';
        $table .= '<td colspan="2" style="text-align:center; font-size:14px">$ ' . number_format($item->getTotalToPay()) . '</td>';
        $table .= '</tr>';
    
    $table .= '<tr>';
    $table .= '<td colspan="4" style="font-weight:bold; text-align:center; font-size:14px; border-top:2px solid rgb(200,200,200)"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px; border-top:2px solid rgb(200,200,200)"></td>';
    $table .= '<td style="font-weight:bold; text-align:right; font-size:14px; border-top:2px solid rgb(200,200,200)">TOTAL:</td>';
    $table .= '<td colspan="2" style="font-weight:bold; text-align:center; font-size:14px; border-top:2px solid rgb(200,200,200)">$ ' . number_format($item->getTotalToPay()) . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td colspan="4" style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:right; font-size:14px">PAGADO:</td>';
    $table .= '<td colspan="2" style="font-weight:bold; text-align:center; font-size:14px">$ ' . number_format($item->getPaid()) . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td colspan="4" style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:right; font-size:14px">PENDIENTE:</td>';
    $table .= '<td colspan="2" style="font-weight:bold; text-align:center; font-size:14px">$ ' . number_format($item->getPendingPayment()) . '</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    $table = '<br><br><table nobr="true" cellpadding="3">';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; text-align:center; font-weight:bold">GRACIAS POR TU COMPRA</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->setOpenCell(true);
    $pdf->writeHTML($table);
    
    ob_clean();
    $pdf->Output('Factura.pdf', 'I');
});
$service->publish();