<?php

require_once '../../Libs/TCPdf/tcpdf.php';

class Helpers {
    
    public static function PDFDocument($title) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
        $pdf->SetCreator("Uniexpress Solutions.");
        $pdf->SetAuthor('Uniexpress Solutions.');
        $pdf->SetTitle($title);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->setPrintHeader(FALSE);
        $pdf->setPrintFooter(FALSE);
        $pdf->setImageScale(1.5);
        return $pdf;
    }
    
}