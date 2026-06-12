<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
require_once 'Helpers.php';

use Cloud\Engine\PHP\WebService\CloudEngineWebService;
use Cloud\Engine\PHP\WebService\CloudEngineWebServiceParameterText;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

$service = new CloudEngineWebService();
$service->addParameterObj(new CloudEngineWebServiceParameterText("IdShipment", 36, true));
$service->setCallback(function() use ($service) {
    $shipment = ShippingDAO::getShippingById($service->getParameter("IdShipment")->getValue());
    
    $pdf = Helpers::PDFDocument("Guía");
    $pdf->AddPage();

    // Uniexpress logotype
    $pdf->setJPEGQuality(100);
    $pdf->ImageSVG("../../Static/Images/logotype.svg", 155, 15, 50, 23, "", "", "", 0, true);

    // Annulled
    if ($shipment->wasAnnulled()) {
        $pdf->SetFont('helvetica', 'B', 50);
        $pdf->SetTextColor(255,220,220);
        $pdf->SetXY(10, 10);
        $pdf->MultiCell(195, 100, "ANULADA", 0, 'C', false, 0, "", "", true, 0, false, true, 60, "M", false);
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
    $table .= '<td style="font-size:15px">International Shipping Logistics<br/>13790 NW 4th Street, Suite 107,<br/>Sunrise, FL 33325.<br/>Office: 954-835-5933<br/>info@uniexpresssolutions.com<br/>www.uniexpresssolutions.com</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    // Division line
    $pdf->Line(10, 67, 206, 67, array('width' => 0.5, 'color' => array(200, 200, 200)));
    
    $customer = $shipment->getPurchases()[0]->getCustomer();
    $language = $customer->getLanguage();
    
    $billToLabel = $language == Customer::LANGUAGE_SPANISH ? "Facturado a" : "Bill to";
    $trackingNoLabel = $language == Customer::LANGUAGE_SPANISH ? "Rastreo" : "Tracking No.";
    $purchasesLabel = $language == Customer::LANGUAGE_SPANISH ? "Compras" : "Purchases";
    $contentLabel = $language == Customer::LANGUAGE_SPANISH ? "Contenido" : "Content";
    $weightLabel = $language == Customer::LANGUAGE_SPANISH ? "Peso" : "Weight";
    $descriptionLabel = $language == Customer::LANGUAGE_SPANISH ? "LIQUIDACIÓN" : "DESCRIPTION";
    $declaredValueLabel = $language == Customer::LANGUAGE_SPANISH ? "Valor declarado" : "Declared value";
    $taxDutyLabel = $language == Customer::LANGUAGE_SPANISH ? "Gestión aduanera" : "Customs processing fee";
    $freightCostLabel = $language == Customer::LANGUAGE_SPANISH ? "Flete" : "Freight cost";
    $insuranceLabel = $language == Customer::LANGUAGE_SPANISH ? "Seguro" : "Insurance";
    $aditionalCostLabel = $language == Customer::LANGUAGE_SPANISH ? "Valor adicional" : "Aditional cost";
    $commentsLabel = $language == Customer::LANGUAGE_SPANISH ? "Comentarios" : "Comments";
    $sequenceLabel = $language == Customer::LANGUAGE_SPANISH ? "Sec." : "Sec.";
    $totalLabel = $language == Customer::LANGUAGE_SPANISH ? "Total" : "Total";
    $pendingLabel = $language == Customer::LANGUAGE_SPANISH ? "Pendiente" : "Pending";
    $paidLabel = $language == Customer::LANGUAGE_SPANISH ? "Pagado" : "Paid";
    
    // Customer and invoice info
    $table = '<br/><br/><br/><table cellpadding="" style="width:100%">';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; font-weight:bold; width: 70%">' . ($billToLabel) . '</td>';
    $table .= '<td style="font-size:22px; font-weight:bold; width: 30%; text-align:right"><span style="font-size:14px">' . ($trackingNoLabel) . '</span> ' . $shipment->getShippingNumber() . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px;">' . $customer->getNames() . ' (' . $customer->getLockerNumber() . ')</td>';
    $table .= '<td style="font-size:14px; text-align:right">' . $shipment->getCreatedTimestampPDFFormat($customer->getLanguage()) . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px;">' . $customer->getAddress() . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px;">' . $customer->getCity()->getName() . ', ' . $customer->getCity()->getCountry()->getName() . '</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    // Items
    $table = '<br/><br/><table cellpadding="3">';
    $table .= '<tr>';
    $table .= '<td colspan="6" style="background-color:gray; font-weight:bold; color:white; text-align:center; font-size:14px">' . strtoupper($purchasesLabel) . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td colspan="3" style="font-weight:bold; text-align:center; font-size:14px">' . $trackingNoLabel . '</td>';
    $table .= '<td colspan="3" style="font-weight:bold; text-align:center; font-size:14px">' . $contentLabel . '</td>';
    $table .= '</tr>';
    $purchases = $shipment->getPurchases();
    foreach ($purchases as $p) {
        $table .= '<tr>';
        $table .= '<td colspan="3" style="font-size:14px">' . $p->getTrackingNumber() . '</td>';
        $table .= '<td colspan="3" style="font-size:14px">' . $p->getContent() . '</td>';
        $table .= '</tr>';
    }
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    // Sale
    $table = '<br><table nobr="true" border="0" cellpadding="3">';
    $table .= '<tr>';
    $table .= '<td colspan="4" style="background-color:gray; font-weight:bold; color:white; text-align:center; font-size:14px">' . strtoupper($descriptionLabel) . '</td>';
    $table .= '</tr>';
    
    if ($shipment->getNetWeight() > 0) $table .= '<tr><td width="85%" colspan="3" style="font-size:14px; text-align:right">' . ($weightLabel) . '</td><td width="15%" style="font-size:14px; text-align:right">' . $shipment->getNetWeight() . '</td></tr>';
    if ($shipment->getDeclaredValue() > 0) $table .= '<tr><td width="85%" colspan="3" style="font-size:14px; text-align:right">' . ($declaredValueLabel) . '</td><td style="font-size:14px; text-align:right">' . number_format($shipment->getDeclaredValue(),2) . ' ' . $shipment->getCurrency() . '</td></tr>';
    if ($shipment->getTax() > 0) $table .= '<tr><td width="85%" colspan="3" style="font-size:14px; text-align:right">' . ($taxDutyLabel) . '</td><td style="font-size:14px; text-align:right">' . number_format($shipment->getTax(),2) . ' ' . $shipment->getCurrency() . '</td></tr>';
    if ($shipment->getFreight() > 0) $table .= '<tr><td width="85%" colspan="3" style="font-size:14px; text-align:right">' . ($freightCostLabel) . '</td><td style="font-size:14px; text-align:right">' . number_format($shipment->getFreight(),2) . ' ' . $shipment->getCurrency() . '</td></tr>';
    if ($shipment->getSecure() > 0) $table .= '<tr><td width="85%" colspan="3" style="font-size:14px; text-align:right">' . ($insuranceLabel) . '</td><td style="font-size:14px; text-align:right">' . number_format($shipment->getSecure(),2) . ' ' . $shipment->getCurrency() . '</td></tr>';
    if ($shipment->getAdditionalValue() != null) $table .= '<tr><td width="85%" colspan="3" style="font-size:14px; text-align:right">' . ($aditionalCostLabel) . '</td><td style="font-size:14px; text-align:right">' . number_format($shipment->getAdditionalValue(),2) . ' ' . $shipment->getCurrency() . '</td></tr>';
    $table .= '<tr><td width="85%" colspan="3" style="font-size:14px; text-align:right">' . ($sequenceLabel) . '</td><td style="font-size:14px; text-align:right">' . $shipment->getSequenceNumber() . '</td></tr>';
    
    $table .= '<tr>';
    $table .= '<td colspan="3" width="85%" style="border-top:2px solid rgb(200,200,200); text-align:right; font-size:14px; font-weight:bold">' . ($totalLabel) . '</td>';
    $table .= '<td width="15%" style="border-top:2px solid rgb(200,200,200); text-align:right; font-size:14px; font-weight:bold;">$ ' . number_format($shipment->getTotal(), 2) . " " . $shipment->getCurrency() . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td colspan="3" width="85%" style="text-align:right; font-size:14px; font-weight:bold">' . ($pendingLabel) . '</td>';
    $table .= '<td width="15%" style="font-size:14px; font-weight:bold; text-align:right">$ ' . number_format($shipment->getPendingPayment(),2) . " " . $shipment->getCurrency() . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td colspan="3" width="85%" style="text-align:right; font-size:14px; font-weight:bold">' . ($paidLabel) . '</td>';
    $table .= '<td width="15%" style="font-size:14px; font-weight:bold; text-align:right">$ ' . number_format($shipment->getTotalPartialPayments(),2) . " " . $shipment->getCurrency() . '</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    // Comments
    if ($shipment->getAdditionalValueDescription() != "") {
        $table = '<br/><table nobr="true" cellpadding="3">';
        $table .= '<tr>';
        $table .= '<td style="background-color:gray; font-weight:bold; color:white; text-align:center; font-size:14px">' . strtoupper($commentsLabel) . '</td>';
        $table .= '</tr>';
        $table .= '<tr>';
        $table .= '<td style="font-size:14px">' . $shipment->getAdditionalValueDescription() . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $pdf->writeHTML($table);
    }
    
    $table = '<br><br><table nobr="true" cellpadding="3">';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; text-align:center">WE PACK. WE SHIP. WE DELIVER.</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td style="font-size:14px; text-align:center; font-weight:bold">THANK YOU FOR YOUR BUSINESS</td>';
    $table .= '</tr>';
    $table .= '</table>';
    $pdf->writeHTML($table);
    
    ob_clean();
    $pdf->Output($shipment->getShippingNumber() . '.pdf', 'I');
});
$service->publish();