<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
require_once 'Helpers.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdBill", 36, true));
$service->setCallback(function() use ($service) {
    $bill = BillDAO::getBillById($service->getParameter("IdBill")->getValue());
    
    $pdf = Helpers::PDFDocument("Factura");
    $pdf->AddPage();

    // Uniexpress logotype
    $pdf->setJPEGQuality(100);
    $pdf->ImageSVG("../../Static/Images/logotype.svg", 155, 15, 50, 23, "", "", "", 0, true);

    // Annulled
    if ($bill->wasAnnulled()) {
        $pdf->SetFont('helvetica', 'B', 50);
        $pdf->SetTextColor(255,220,220);
        $pdf->SetXY(10, 10);
        $pdf->MultiCell(195, 100, "VOID", 0, 'C', false, 0, "", "", true, 0, false, true, 60, "M", false);
    }
    
    $pdf->SetFont('helvetica', '', 16);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(0, 10);
    
    // Company info
    $table = '<br><br><table cellpadding="3">';
    $table .= '<tr>';
    $table .= '<td style="font-size:26px;font-weight:bold">Uniexpress Solutions Inc.</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:15px">International Shipping Logistics<br/>13794 NW 4th Street, Suite 201,<br/>Sunrise, FL 33325.<br/>Office: 954-835-5933<br/>info@uniexpresssolutions.com<br/>www.uniexpresssolutions.com</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    // Division line
    $pdf->Line(10, 67, 206, 67, array('width' => 0.5, 'color' => array(200, 200, 200)));
    
    // Customer info
    $table = '<br/><br/><br/><table cellpadding="" cellspacing="5px" style="width:100%">';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; font-weight:bold; width: 34%;">FROM</td>';
    $table .= '<td style="font-size:14px; font-weight:bold; width: 2%;">&nbsp;</td>';
    $table .= '<td style="font-size:14px; font-weight:bold; width: 34%">TO</td>';
    $table .= '<td style="font-size:22px; font-weight:bold; width: 30%; text-align:right"><span style="font-size:14px">INVOICE</span> ' . $bill->getBillNumber() . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px;">' . $bill->getFrom() . '</td>';
    $table .= '<td style="font-size:14px;">&nbsp;</td>';
    $table .= '<td style="font-size:14px;">' . $bill->getTo() . '</td>';
    $table .= '<td style="font-size:14px; text-align:right">' . $bill->getCreatedTimestampPDFFormat() . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px;">' . $bill->getFromAddress() . '</td>';
    $table .= '<td style="font-size:14px;">&nbsp;</td>';
    $table .= '<td style="font-size:14px;">' . $bill->getToAddress() . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px;">' . $bill->getFromPhone() . '</td>';
    $table .= '<td style="font-size:14px;">&nbsp;</td>';
    $table .= '<td style="font-size:14px;">' . $bill->getToPhone() . '</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    // Items
    $items = $bill->getItems();
    $table = '<br/><br/><table border="0" cellpadding="3">';
    $table .= '<tr>';
    $table .= '<td colspan="4" style="font-weight:bold; text-align:center; font-size:14px; background-color:gray; color:white">DESCRIPTION</td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px; background-color:gray; color:white">BOX #</td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px; background-color:gray; color:white">WEIGHT</td>';
    $table .= '<td colspan="2" style="font-weight:bold; text-align:center; font-size:14px; background-color:gray; color:white">AMOUNT</td>';
    $table .= '</tr>';
    foreach ($items as $i) {
        $table .= '<tr>';
        $table .= '<td colspan="4" style="font-size:14px">' . utf8_encode($i->getDescription()) . '</td>';
        $table .= '<td style="text-align:center; font-size:14px">' . $i->getBoxNumber() . '</td>';
        $table .= '<td style="text-align:center; font-size:14px">' . $i->getWeight() . '</td>';
        $table .= '<td colspan="2" style="text-align:center; font-size:14px">$ ' . number_format($i->getAmount(),2) . " " . $bill->getCurrency() . '</td>';
        $table .= '</tr>';
    }
    $table .= '<tr>';
    $table .= '<td colspan="4" style="font-weight:bold; text-align:center; font-size:14px; border-top:2px solid rgb(200,200,200)"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px; border-top:2px solid rgb(200,200,200)"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px; border-top:2px solid rgb(200,200,200)">TOTAL</td>';
    $table .= '<td colspan="2" style="font-weight:bold; text-align:center; font-size:14px; border-top:2px solid rgb(200,200,200)">$ ' . number_format($bill->getTotal(),2) . " " . $bill->getCurrency() . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td colspan="4" style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px">PAID</td>';
    $table .= '<td colspan="2" style="font-weight:bold; text-align:center; font-size:14px">$ ' . number_format($bill->getTotalPartialPayments(),2) . " " . $bill->getCurrency() . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td colspan="4" style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px"></td>';
    $table .= '<td style="font-weight:bold; text-align:center; font-size:14px">PENDING</td>';
    $table .= '<td colspan="2" style="font-weight:bold; text-align:center; font-size:14px">$ ' . number_format($bill->getPendingPayment(),2) . " " . $bill->getCurrency() . '</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    $table = '<br><br><table nobr="true" cellpadding="3">';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; text-align:center">WE PACK. WE SHIP. WE DELIVER.</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; text-align:center; font-weight:bold">THANK YOU FOR YOUR BUSINESS</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->setOpenCell(true);
    $pdf->writeHTML($table);
    
    ob_clean();
    $pdf->Output($bill->getBillNumber() . '.pdf', 'I');
});
$service->publish();
